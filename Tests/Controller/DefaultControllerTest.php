<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Controller;

use BlackBoxCode\Pando\ContentBundle\Controller\DefaultController;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormPageDocument;
use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\ContentBundle\Exception\Controller\InvalidContentDocumentException;
use BlackBoxCode\Pando\ContentBundle\Service\FormContainerService;
use BlackBoxCode\Pando\ContentBundle\Service\FormService;
use BlackBoxCode\Pando\ContentBundle\Service\ForwardResolverService;
use BlackBoxCode\Pando\ContentBundle\Service\TemplateFinderService;
use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockBuilder */
    private $mDefaultController;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Kernel */
    private $mKernel;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormService */
    private $mFormService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RequestStack */
    private $mRequestStack;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormContainerService */
    private $mFormContainerService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|DynamicRouter */
    private $mDynamicRouter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormFactory */
    private $mFormFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ForwardResolverService */
    private $mForwardResolverService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|TemplateFinderService */
    private $mTemplateFinderService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Request */
    private $mRequest;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormInterface */
    private $mForm;


    public function setUp()
    {
        $this->mKernel = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Kernel')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->mFormService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormService');
        $this->mRequestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');
        $this->mFormContainerService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormContainerService');
        $this->mDynamicRouter = $this
            ->getMockBuilder('Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->mFormFactory = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->mForwardResolverService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\ForwardResolverService');
        $this->mTemplateFinderService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\TemplateFinderService');

        $this->mDefaultController = $this
            ->getMockBuilder('BlackBoxCode\Pando\ContentBundle\Controller\DefaultController')
            ->disableOriginalConstructor()
        ;

        $this->mRequest = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->mForm = $this->getMock('Symfony\Component\Form\FormInterface');
    }

    /**
     * @param DefaultController $mDefaultController
     */
    private function setControllerDependencies(DefaultController $mDefaultController)
    {
        $mDefaultController
            ->setKernel($this->mKernel)
            ->setFormService($this->mFormService)
            ->setRequestStack($this->mRequestStack)
            ->setFormContainerService($this->mFormContainerService)
            ->setDynamicRouter($this->mDynamicRouter)
            ->setFormFactory($this->mFormFactory)
            ->setForwardResolverService($this->mForwardResolverService)
            ->setTemplateFinderService($this->mTemplateFinderService)
        ;
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Controller\InvalidContentDocumentException
     */
    public function indexAction_notAPage()
    {
        $block = new BlockDocument();

        /** @var \PHPUnit_Framework_MockObject_MockObject|DefaultController $mDefaultController */
        $mDefaultController = $this->mDefaultController
            ->setMethods(['forward'])
            ->getMock()
        ;
        $this->setControllerDependencies($mDefaultController);

        $mDefaultController->indexAction($this->mRequest, $block, null);
    }

    /**
     * @test
     */
    public function indexAction_shouldNotProcess()
    {
        $page = new PageDocument();
        $response = new Response();

        /** @var \PHPUnit_Framework_MockObject_MockObject|DefaultController $mDefaultController */
        $mDefaultController = $this->mDefaultController
            ->setMethods(['parentIndexAction'])
            ->getMock()
        ;
        $this->setControllerDependencies($mDefaultController);

        $this->mFormService
            ->expects($this->once())
            ->method('shouldFormProcess')
            ->with($page)
            ->willReturn(false)
        ;

        $mDefaultController
            ->expects($this->once())
            ->method('parentIndexAction')
            ->with($this->mRequest, $page, null)
            ->willReturn($response)
        ;

        $return = $mDefaultController->indexAction($this->mRequest, $page, null);
        $this->assertSame($response, $return);
    }

    /**
     * @test
     */
    public function indexAction_formInvalid()
    {
        $page = new PageDocument();
        $forwardPage = new PageDocument();
        $response = new Response();

        /** @var \PHPUnit_Framework_MockObject_MockObject|DefaultController $mDefaultController */
        $mDefaultController = $this->mDefaultController
            ->setMethods(['forward'])
            ->getMock()
        ;
        $this->setControllerDependencies($mDefaultController);

        $this->mFormService
            ->expects($this->once())
            ->method('shouldFormProcess')
            ->with($page)
            ->willReturn(true)
        ;

        $this->mFormService
            ->expects($this->once())
            ->method('handleRequest')
        ;

        $this->mFormContainerService
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($this->mForm)
        ;

        $this->mForm
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false)
        ;

        $this->mForwardResolverService
            ->expects($this->once())
            ->method('resolve')
            ->with($page, $this->mForm, false)
            ->willReturn($forwardPage)
        ;

        $mDefaultController
            ->expects($this->once())
            ->method('forward')
            ->with($forwardPage, null)
            ->willReturn($response)
        ;

        $return = $mDefaultController->indexAction($this->mRequest, $page, null);
        $this->assertSame($response, $return);
    }

    /**
     * @test
     */
    public function indexAction_formValid()
    {
        $formName = 'the_form';

        $form = new FormDocument();
        $form->setName($formName);

        $formPage = new FormPageDocument();
        $formPage->setForm($form);

        $page = new PageDocument();
        $page->addFormPage($formPage);

        $forwardPage = new PageDocument();
        $response = new Response();

        /** @var \PHPUnit_Framework_MockObject_MockObject|DefaultController $mDefaultController */
        $mDefaultController = $this->mDefaultController
            ->setMethods(['forward'])
            ->getMock()
        ;
        $this->setControllerDependencies($mDefaultController);

        $this->mFormService
            ->expects($this->once())
            ->method('shouldFormProcess')
            ->with($page)
            ->willReturn(true)
        ;

        $this->mFormService
            ->expects($this->once())
            ->method('handleRequest')
        ;

        $this->mFormContainerService
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($this->mForm)
        ;

        $this->mForm
            ->expects($this->once())
            ->method('getName')
            ->willReturn($formName)
        ;

        $this->mForm
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $this->mFormService
            ->expects($this->once())
            ->method('processFormPage')
            ->with($formPage)
        ;

        $this->mForwardResolverService
            ->expects($this->once())
            ->method('resolve')
            ->with($page, $this->mForm, true)
            ->willReturn($forwardPage)
        ;

        $mDefaultController
            ->expects($this->once())
            ->method('forward')
            ->with($forwardPage, null)
            ->willReturn($response)
        ;

        $return = $mDefaultController->indexAction($this->mRequest, $page, null);
        $this->assertSame($response, $return);
    }

    /**
     * @test
     */
    public function forward()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|DefaultController $mDefaultController */
        $mDefaultController = $this->mDefaultController
            ->setMethods(['indexAction'])
            ->getMock()
        ;
        $this->setControllerDependencies($mDefaultController);

        $page = new PageDocument();
        $templateName = 'the_template';
        $subRequest = new Request();
        $response = new Response();

        $this->mRequestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($this->mRequest)
        ;

        $this->mRequest
            ->expects($this->once())
            ->method('duplicate')
            ->with([], null, [
                '_controller'     => DefaultController::GLOBAL_CONTROLLER_ACTION,
                'contentDocument' => $page,
                'contentTemplate' => $templateName
            ])
            ->willReturn($subRequest)
        ;

        $this->mKernel
            ->expects($this->once())
            ->method('handle')
            ->with($subRequest, HttpKernelInterface::SUB_REQUEST)
            ->willReturn($response)
        ;

        $return = $mDefaultController->forward($page, $templateName);
        $this->assertSame($response, $return);
    }
}
