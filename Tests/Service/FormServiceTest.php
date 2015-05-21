<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormBlockMethodDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormPageDocument;
use BlackBoxCode\Pando\ContentBundle\Document\MethodDocument;
use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\ContentBundle\Service\FormContainerService;
use BlackBoxCode\Pando\ContentBundle\Service\FormService;
use BlackBoxCode\Pando\ContentBundle\Service\MethodService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
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

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormFactory */
    private $mFormFactory;


    public function setUp()
    {
        $this->mMethodService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\MethodService');
        $this->mFormContainerService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormContainerService');
        $this->mRequestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');
        $this->mFormFactory = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->formService = new FormService();
        $this->formService
            ->setMethodService($this->mMethodService)
            ->setFormContainerService($this->mFormContainerService)
            ->setRequestStack($this->mRequestStack)
            ->setFormFactory($this->mFormFactory)
        ;
    }

    public function processBlock_formNotSubmitted()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface $mForm */
        $mForm = $this->getMock('Symfony\Component\Form\FormInterface');

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
    public function processBlock_notSubmitted()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface $mForm */
        $mForm = $this->getMock('Symfony\Component\Form\FormInterface');

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
        $block = new BlockDocument();
        $block->addFormBlockMethod(new FormBlockMethodDocument());

        /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface $mForm */
        $mForm = $this->getMock('Symfony\Component\Form\FormInterface');

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

        $return = $this->formService->processBlock($block);
        $this->assertFalse($return);
    }

    /**
     * @test
     */
    public function processBlock_hasMethods()
    {
        $formBlockMethodDocument1 = new FormBlockMethodDocument();
        $formBlockMethodDocument1->setMethod(new MethodDocument());

        $formBlockMethodDocument2 = new FormBlockMethodDocument();
        $formBlockMethodDocument2->setMethod(new MethodDocument());

        $block = new BlockDocument();
        $block
            ->addFormBlockMethod($formBlockMethodDocument1)
            ->addFormBlockMethod($formBlockMethodDocument2)
        ;

        /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface $mForm */
        $mForm = $this->getMock('Symfony\Component\Form\FormInterface');

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

    /**
     * @test
     */
    public function processFormPage()
    {
        $formPageDocument = new FormPageDocument();
        $formPageDocument
            ->addMethod(new MethodDocument())
            ->addMethod(new MethodDocument())
        ;

        $this->mMethodService
            ->expects($this->exactly(2))
            ->method('call')
            ->with($this->isInstanceOf('BlackBoxCode\Pando\ContentBundle\Document\MethodDocument'))
        ;

        $this->formService->processFormPage($formPageDocument);
    }

    /**
     * @test
     */
    public function getSubmittedFormName()
    {
        $formName = 'the_form';
        $request = new Request([], ['form' => ["name" => $formName]]);

        $this->mRequestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $return = $this->formService->getSubmittedFormName();
        $this->assertEquals($formName, $return);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\FormNotFoundException
     */
    public function getSubmittedFormName_noForm()
    {
        $request = new Request();

        $this->mRequestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $this->formService->getSubmittedFormName();
    }

    /**
     * @test
     */
    public function shouldFormProcess_notRoot()
    {
        $this->mRequestStack
            ->expects($this->once())
            ->method('getParentRequest')
            ->willReturn(new Request())
        ;

        $this->assertFalse($this->formService->shouldFormProcess(new PageDocument()));
    }

    /**
     * @test
     */
    public function shouldFormProcess_notPost()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|RequestStack $mRequest */
        $mRequest = $this->getMock('Symfony\Component\HttpFoundation\Request');

        $this->mRequestStack
            ->expects($this->once())
            ->method('getParentRequest')
            ->willReturn(null)
        ;

        $this->mRequestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($mRequest)
        ;

        $mRequest
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Request::METHOD_GET)
        ;

        $this->assertFalse($this->formService->shouldFormProcess(new PageDocument()));
    }

    /**
     * @test
     */
    public function shouldFormProcess_notInFormNames()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|FormService $mFormService */
        $mFormService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormService', ['getSubmittedFormName']);
        $mFormService->setRequestStack($this->mRequestStack);

        /** @var \PHPUnit_Framework_MockObject_MockObject|PageDocument $mPage */
        $mPage = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\PageDocument');

        /** @var \PHPUnit_Framework_MockObject_MockObject|RequestStack $mRequest */
        $mRequest = $this->getMock('Symfony\Component\HttpFoundation\Request');

        $this->mRequestStack
            ->expects($this->once())
            ->method('getParentRequest')
            ->willReturn(null)
        ;

        $this->mRequestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($mRequest)
        ;

        $mRequest
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Request::METHOD_POST)
        ;

        $mPage
            ->expects($this->once())
            ->method('getFormNames')
            ->willReturn(new ArrayCollection(['form_1', 'form_3']))
        ;

        $mFormService
            ->expects($this->once())
            ->method('getSubmittedFormName')
            ->willReturn('form_2')
        ;

        $this->assertFalse($mFormService->shouldFormProcess($mPage));
    }

    /**
     * @test
     */
    public function shouldFormProcess_yesItShould()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|FormService $mFormService */
        $mFormService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormService', ['getSubmittedFormName']);
        $mFormService->setRequestStack($this->mRequestStack);

        /** @var \PHPUnit_Framework_MockObject_MockObject|PageDocument $mPage */
        $mPage = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\PageDocument');

        /** @var \PHPUnit_Framework_MockObject_MockObject|RequestStack $mRequest */
        $mRequest = $this->getMock('Symfony\Component\HttpFoundation\Request');

        $this->mRequestStack
            ->expects($this->once())
            ->method('getParentRequest')
            ->willReturn(null)
        ;

        $this->mRequestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($mRequest)
        ;

        $mRequest
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Request::METHOD_POST)
        ;

        $mPage
            ->expects($this->once())
            ->method('getFormNames')
            ->willReturn(new ArrayCollection(['form_1', 'form_3']))
        ;

        $mFormService
            ->expects($this->once())
            ->method('getSubmittedFormName')
            ->willReturn('form_1')
        ;

        $this->assertTrue($mFormService->shouldFormProcess($mPage));
    }

    /**
     * @test
     */
    public function handleRequest()
    {
        $formName = 'the_form';

        /** @var \PHPUnit_Framework_MockObject_MockObject|FormService $mFormService */
        $mFormService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormService', ['getSubmittedFormName']);
        $mFormService
            ->setFormContainerService($this->mFormContainerService)
            ->setFormFactory($this->mFormFactory)
            ->setRequestStack($this->mRequestStack)
        ;

        /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface $mForm */
        $mForm = $this->getMock('Symfony\Component\Form\FormInterface');

        $mFormService
            ->expects($this->once())
            ->method('getSubmittedFormName')
            ->willReturn($formName)
        ;

        $this->mFormFactory
            ->expects($this->once())
            ->method('create')
            ->with($formName)
            ->willReturn($mForm)
        ;

        $request = new Request();

        $this->mRequestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $mForm
            ->expects($this->once())
            ->method('handleRequest')
            ->with($request)
        ;

        $this->mFormContainerService
            ->expects($this->once())
            ->method('setForm')
            ->with($mForm)
        ;

        $mFormService->handleRequest();
    }
}
