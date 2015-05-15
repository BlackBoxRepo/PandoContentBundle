<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\BlockVariableDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\MethodDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Service\BlockVariableService;
use BlackBoxCode\Pando\Bundle\ContentBundle\Service\FormBlockContainerService;
use BlackBoxCode\Pando\Bundle\ContentBundle\Service\MethodService;
use Doctrine\Common\Collections\ArrayCollection;

class BlockVariableServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var BlockVariableService */
    private $blockVariableService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormBlockContainerService */
    private $mFormBlockContainerService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|MethodService */
    private $mMethodService;


    public function setUp()
    {
        $this->mMethodService = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Service\MethodService');
        $this->mFormBlockContainerService = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Service\FormBlockContainerService');

        $this->blockVariableService = new BlockVariableService();
        $this->blockVariableService
            ->setFormBlockContainerService($this->mFormBlockContainerService)
            ->setMethodService($this->mMethodService)
        ;
    }

    /**
     * @test
     */
    public function populateBlockVariables()
    {
        $variable1 = new BlockVariableDocument();
        $variable1
            ->setName('variable 1')
            ->setMethod(new MethodDocument())
        ;

        $variable2 = new BlockVariableDocument();
        $variable2
            ->setName('variable 2')
            ->setMethod(new MethodDocument())
        ;

        $block = new BlockDocument();
        $block
            ->addVariable($variable1)
            ->addVariable($variable2)
        ;

        $this->mMethodService
            ->expects($this->exactly(2))
            ->method('call')
            ->with($this->isInstanceOf(get_class(new MethodDocument())))
            ->willReturnOnConsecutiveCalls(
                'abc',
                ['a', 'b', 'c']
            )
        ;

        $return = $this->blockVariableService->populateBlockVariables($block);
        $this->assertInstanceOf(get_class(new ArrayCollection([])), $return);
        $this->assertArrayHasKey($variable1->getName(), $return);
        $this->assertArrayHasKey($variable2->getName(), $return);
        $this->assertContains('abc', $return);
        $this->assertContains(['a', 'b', 'c'], $return);
    }
}
