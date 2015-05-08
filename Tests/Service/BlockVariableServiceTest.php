<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Block;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\BlockVariable;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Method;
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
        $variable1 = new BlockVariable();
        $variable1
            ->setName('variable 1')
            ->setMethod(new Method())
        ;

        $variable2 = new BlockVariable();
        $variable2
            ->setName('variable 2')
            ->setMethod(new Method())
        ;

        $block = new Block();
        $block
            ->addVariable($variable1)
            ->addVariable($variable2)
        ;

        $this->mMethodService
            ->expects($this->exactly(2))
            ->method('call')
            ->with($this->isInstanceOf(get_class(new Method())))
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
