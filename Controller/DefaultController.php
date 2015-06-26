<?php
namespace BlackBoxCode\Pando\ContentBundle\Controller;

use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\ContentBundle\Exception\Controller\InvalidContentDocumentException;
use BlackBoxCode\Pando\ContentBundle\Service\FormContainerService;
use BlackBoxCode\Pando\ContentBundle\Service\FormService;
use BlackBoxCode\Pando\ContentBundle\Service\ForwardResolverService;
use BlackBoxCode\Pando\ContentBundle\Service\TemplateFinderService;
use Symfony\Cmf\Bundle\ContentBundle\Controller\ContentController;
use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class DefaultController extends ContentController
{
    const GLOBAL_CONTROLLER_ACTION = 'cmf_content.controller:indexAction';

    /** @var HttpKernelInterface */
    private $kernel;

    /** @var FormService */
    private $formService;

    /** @var RequestStack */
    private $requestStack;

    /** @var FormContainerService */
    private $formContainerService;

    /** @var DynamicRouter */
    private $dynamicRouter;

    /** @var FormFactory */
    private $formFactory;

    /** @var ForwardResolverService */
    private $forwardResolverService;

    /** @var TemplateFinderService */
    private $templateFinderService;


    /**
     * @param HttpKernelInterface $kernel
     *
     * @return $this
     */
    public function setKernel(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;

        return $this;
    }

    /**
     * @param FormService $formService
     *
     * @return $this
     */
    public function setFormService(FormService $formService)
    {
        $this->formService = $formService;

        return $this;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return $this
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        return $this;
    }

    /**
     * @param FormContainerService $formContainerService
     *
     * @return $this
     */
    public function setFormContainerService(FormContainerService $formContainerService)
    {
        $this->formContainerService = $formContainerService;

        return $this;
    }

    /**
     * @param DynamicRouter $dynamicRouter
     *
     * @return $this
     */
    public function setDynamicRouter(DynamicRouter $dynamicRouter)
    {
        $this->dynamicRouter = $dynamicRouter;

        return $this;
    }

    /**
     * @param FormFactory $formFactory
     *
     * @return $this
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * @param ForwardResolverService $forwardResolverService
     *
     * @return $this
     */
    public function setForwardResolverService(ForwardResolverService $forwardResolverService)
    {
        $this->forwardResolverService = $forwardResolverService;

        return $this;
    }

    /**
     * @param TemplateFinderService $templateFinderService
     *
     * @return $this
     */
    public function setTemplateFinderService(TemplateFinderService $templateFinderService)
    {
        $this->templateFinderService = $templateFinderService;

        return $this;
    }

    public function indexAction(Request $request, $contentDocument, $contentTemplate = null)
    {
        if (!$contentDocument instanceof PageDocument) {
            throw new InvalidContentDocumentException(sprintf(
                'Expected contentDocument to be instance of %s. Got %s',
                'PageDocument',
                get_class($contentDocument)
            ));
        }

        /** @var PageDocument $contentDocument */
        if ($this->formService->shouldFormProcess($contentDocument)) {
            $this->formService->handleRequest();

            $form = $this->formContainerService->getForm();
            if ($valid = $form->isValid()) {
                if ($formPage = $contentDocument->getFormPageByFormName($form->getName())) {
                    $this->formService->processFormPage($formPage);
                }
            }

            $forwardPage = $this->forwardResolverService->resolve($contentDocument, $form, $valid);

            return $this->forward($forwardPage, $this->templateFinderService->find($forwardPage));
        }

        return $this->parentIndexAction($request, $contentDocument, $contentTemplate);
    }

    public function parentIndexAction(Request $request, $contentDocument, $contentTemplate = null)
    {
        return parent::indexAction($request, $contentDocument, $contentTemplate);
    }

    /**
     * @param PageDocument $page
     * @param string $templateName
     *
     * @return Response
     */
    public function forward(PageDocument $page, $templateName)
    {
        $request = $this->requestStack->getCurrentRequest();
        $subRequest = $request->duplicate([], null, [
            '_controller'     => self::GLOBAL_CONTROLLER_ACTION,
            'contentDocument' => $page,
            'contentTemplate' => $templateName
        ]);

        return $this->kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }
}
