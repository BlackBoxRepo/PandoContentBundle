<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Block;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\BlockVariable;
use Doctrine\Common\Collections\ArrayCollection;

class BlockVariableService
{
    /** @var MethodService */
    private $methodService;

    /** @var FormBlockContainerService */
    private $formBlockContainerService;


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
        $results = new ArrayCollection();

        /** @var BlockVariable $variable */
        foreach ($block->getVariables() as $variable) {
            $results->set($variable->getName(), $this->methodService->call($variable->getMethod()));
        }

        return $results;
    }
}
