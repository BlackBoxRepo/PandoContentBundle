<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\BlockVariableDocument;
use Doctrine\Common\Collections\ArrayCollection;

class BlockVariableService
{
    /** @var MethodService */
    private $methodService;

    /** @var FormBlockContainerService */
    private $formBlockContainerService;


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
     * Returns an ArrayCollection where the keys are the BlockVariableDocument names
     * and the values are the return of the associated method
     *
     * @param BlockDocument $block
     *
     * @return ArrayCollection
     */
    public function populateBlockVariables(BlockDocument $block)
    {
        $results = new ArrayCollection();

        /** @var BlockVariableDocument $variable */
        foreach ($block->getVariables() as $variable) {
            $results->set($variable->getName(), $this->methodService->call($variable->getMethod()));
        }

        return $results;
    }
}
