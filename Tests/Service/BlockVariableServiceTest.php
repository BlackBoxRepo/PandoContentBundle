<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\BlockVariableDocument;
use BlackBoxCode\Pando\ContentBundle\Document\MethodDocument;
use BlackBoxCode\Pando\ContentBundle\Service\BlockVariableService;
use BlackBoxCode\Pando\ContentBundle\Service\MethodService;
use Doctrine\Common\Collections\ArrayCollection;

class BlockVariableServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var BlockVariableService */
    private $blockVariableService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|MethodService */
    private $mMethodService;


    public function setUp()
    {
        $this->mMethodService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\MethodService');

        $this->blockVariableService = new BlockVariableService();
        $this->blockVariableService->setMethodService($this->mMethodService);
    }

    /**
     * @test
     */
    public function populateBlockVariables()
    {
        $variable1 = new BlockVariableDocument();
        $variable1
            ->setName('variable 1')
            ->setMethodArgument(new MethodDocument())
        ;

        $variable2 = new BlockVariableDocument();
        $variable2
            ->setName('variable 2')
            ->setMethodArgument(new MethodDocument())
        ;

        $block = new BlockDocument();
        $block
            ->addVariable($variable1)
            ->addVariable($variable2)
        ;

        $this->blockVariableService->setBlock($block);

        $this->mMethodService
            ->expects($this->exactly(2))
            ->method('call')
            ->with($this->isInstanceOf(get_class(new MethodDocument())))
            ->willReturnOnConsecutiveCalls(
                'abc',
                ['a', 'b', 'c']
            )
        ;

        $this->blockVariableService->populateBlockVariables();

        $viewVariables = $block->getViewVariables();
        $this->assertInstanceOf(get_class(new ArrayCollection([])), $viewVariables);
        $this->assertArrayHasKey($variable1->getName(), $viewVariables);
        $this->assertArrayHasKey($variable2->getName(), $viewVariables);
        $this->assertContains('abc', $viewVariables);
        $this->assertContains(['a', 'b', 'c'], $viewVariables);
    }
}
