<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use Symfony\Component\HttpFoundation\RequestStack;

class FormService
{
    /** @var MethodService */
    private $methodService;

    /** @var FormBlockContainerService */
    private $formBlockContainerService;

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
     * @param FormBlockContainerService $formBlockContainerService
     *
     * @return $this
     */
    public function setFormBlockContainerService(FormBlockContainerService $formBlockContainerService)
    {
        $this->formBlockContainerService = $formBlockContainerService;

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
