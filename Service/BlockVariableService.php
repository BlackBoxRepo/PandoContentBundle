<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\BlockVariableDocument;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Monolog\Logger;

class BlockVariableService
{
    /** @var MethodService */
    private $methodService;

    /** @var BlockDocument */
    private $block;

    /** @var Logger */
    private $logger;


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
     * @param BlockDocument $block
     *
     * @return $this
     */
    public function setBlock(BlockDocument $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return BlockDocument
     */
    private function getBlock()
    {
        return $this->block;
    }

    /**
     * Sets the blocks viewVariables where the keys are the BlockVariableDocument names
     * and the values are the return of the associated method
     *
     * @return ArrayCollection
     */
    public function populateBlockVariables()
    {
        /** @var BlockVariableDocument $variable */
        foreach ($this->block->getVariables() as $variable) {
            $this->setBlockVariable($variable->getName(), $this->methodService->call($variable->getMethodArgument()));
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setBlockVariable($key, $value)
    {
        $this->getLogger()->addInfo("key is $key, value is $value");
        $this->getBlock()->getViewVariables()->set($key, $value);
    }

    /**
     * @param $key
     */
    public function getBlockVariable($key)
    {
        $this->getBlock()->getViewVariables()->get($key);
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}
