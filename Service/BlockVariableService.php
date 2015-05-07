<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Block;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BlockVariableService
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var MethodService */
    private $methodService;

    /** @var FormBlockContainerService */
    private $formBlockContainerService;


    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * @param MethodService $methodService
     * @return $this
     */
    public function setMethodService(MethodService $methodService)
    {
        $this->methodService = $methodService;
        return $this;
    }

    /**
     * @param FormBlockContainerService $formBlockContainerService
     * @return $this
     */
    public function setFormBlockContainerService(FormBlockContainerService $formBlockContainerService)
    {
        $this->formBlockContainerService = $formBlockContainerService;
        return $this;
    }

    /**
     * Returns an ArrayCollection where the keys are the BlockVariable names
     * and the values are the return of the associated method
     *
     * @param Block $block
     * @return ArrayCollection
     */
    public function populateBlockVariables(Block $block)
    {

    }
}
