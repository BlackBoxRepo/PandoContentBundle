<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use Symfony\Component\HttpFoundation\RequestStack;

class FormService
{
    /** @var MethodService */
    private $methodService;

    /** @var FormContainerService */
    private $formContainerService;

    /** @var RequestStack */
    private $requestStack;

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

    public function processBlock()
    {

    }

    public function processFormPage(FormDocument $formDocument)
    {

    }

    public function getSubmittedFormName(PageDocument $page)
    {

    }

    public function handleRequest()
    {

    }
}
