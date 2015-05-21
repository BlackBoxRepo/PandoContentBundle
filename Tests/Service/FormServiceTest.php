<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\BlockVariableDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Document\MethodDocument;
use BlackBoxCode\Pando\ContentBundle\Service\FormContainerService;
use BlackBoxCode\Pando\ContentBundle\Service\FormService;
use BlackBoxCode\Pando\ContentBundle\Service\MethodService;
use Symfony\Component\HttpFoundation\RequestStack;


class FormServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var FormService */
    private $formService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|MethodService */
    private $mMethodService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormContainerService */
    private $mFormContainerService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RequestStack */
    private $mRequestStack;


    public function setUp()
    {
        $this->mMethodService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\MethodService');
        $this->mFormContainerService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormContainerService');
        $this->mRequestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');

        $this->formService = new FormService();
        $this->formService
            ->setMethodService($this->mMethodService)
            ->setFormContainerService($this->mFormContainerService)
            ->setRequestStack($this->mRequestStack)
        ;
    }

    public function processBlock_formNotSubmitted()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|FormDocument **/
        $mForm = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\FormDocument');

        $this->mFormContainerService
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($mForm)
        ;

        $mForm
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(false)
        ;

        $return = $this->formService->processBlock(new BlockDocument());
        $this->assertFalse($return);
    }

    /**
     * @test
     */
    public function processBlock_hasNoMethods()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|FormDocument **/
        $mForm = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\FormDocument');

        $this->mFormContainerService
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($mForm)
        ;

        $mForm
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true)
        ;

        $return = $this->formService->processBlock(new BlockDocument());
        $this->assertFalse($return);
    }

    /**
     * @test
     */
    public function processBlock_hasMethods()
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

        /** @var \PHPUnit_Framework_MockObject_MockObject|FormDocument **/
        $mForm = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\FormDocument');

        $this->mFormContainerService
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($mForm)
        ;

        $mForm
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true)
        ;

        $this->mMethodService
            ->expects($this->exactly(2))
            ->method('call')
            ->with($this->isInstanceOf('BlackBoxCode\Pando\ContentBundle\Document\MethodDocument'))
        ;

        $return = $this->formService->processBlock($block);
        $this->assertTrue($return);
    }
}
