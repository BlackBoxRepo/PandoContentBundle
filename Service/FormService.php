<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormBlockMethodDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormPageDocument;
use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\FormNotFoundException;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FormService
{
    /** @var MethodService */
    private $methodService;

    /** @var FormContainerService */
    private $formContainerService;

    /** @var RequestStack */
    private $requestStack;

    /** @var FormFactory */
    private $formFactory;


    /**
     * @param MethodService $methodService
     *
     * @return $this
     */
    public function setMethodService(MethodService $methodService)
    {
        $this->methodService = $methodService;

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
     * @param BlockDocument $block
     *
     * @return bool
     */
    public function processBlock(BlockDocument $block)
    {
        $hasMethods = false;

        $form = $this->formContainerService->getForm();
        if ($form->isSubmitted()) {
            $formBlockMethods = $block->getFormBlockMethods();

            /** @var FormBlockMethodDocument $formBlockMethod */
            foreach ($formBlockMethods as $formBlockMethod) {
                if ($method = $formBlockMethod->getMethod()) {
                    $this->methodService->call($method);

                    if (!$hasMethods) {
                        $hasMethods = true;
                    }
                }
            }
        }

        return $hasMethods;
    }

    /**
     * @param FormPageDocument $formPageDocument
     */
    public function processFormPage(FormPageDocument $formPageDocument)
    {
        foreach ($formPageDocument->getMethods() as $method) {
            $this->methodService->call($method);
        }
    }

    /**
     * @return string
     * @throws FormNotFoundException
     */
    public function getSubmittedFormName()
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        if ($form = $request->request->get('form')) {
            return $form['name'];
        }

        throw new FormNotFoundException('The request does not contain a form.');
    }

    /**
     * @param PageDocument $page
     *
     * @return bool
     */
    public function shouldFormProcess(PageDocument $page)
    {
        return $this->requestStack->getParentRequest() === null
            && $this->requestStack->getCurrentRequest()->getMethod() == Request::METHOD_POST
            && $page->getFormNames()->contains($this->getSubmittedFormName())
        ;
    }

    public function handleRequest()
    {
        $form = $this->formFactory->create($this->getSubmittedFormName());
        $this->formContainerService->setForm($form);
        $form->handleRequest($this->requestStack->getCurrentRequest());
    }
}
