<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\BlockVariableDocument;
use Doctrine\Common\Collections\ArrayCollection;

class BlockVariableService
{
    /** @var MethodService */
    private $methodService;


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
     * Sets the blocks viewVariables where the keys are the BlockVariableDocument names
     * and the values are the return of the associated method
     *
     * @param BlockDocument $block
     *
     * @return ArrayCollection
     */
    public function populateBlockVariables(BlockDocument $block)
    {
        $viewVariables = new ArrayCollection();

        /** @var BlockVariableDocument $variable */
        foreach ($block->getVariables() as $variable) {
            $viewVariables->set($variable->getName(), $this->methodService->call($variable->getMethod()));
        }

        $block->setViewVariables($viewVariables);
    }
}
