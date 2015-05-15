<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\AbstractPhpcrDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Service\TemplateFinderService;
use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TemplateFinderServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var TemplateFinderService */
    private $templateFinderService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|DynamicRouter */
    private $mDynamicRouter;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RequestStack */
    private $mRequestStack;

    /** @var \PHPUnit_Framework_MockObject_MockObject|PageDocument */
    private $mPage;


    public function setUp()
    {
        $this->mDynamicRouter = $this
            ->getMockBuilder('Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mRequestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');

        $this->templateFinderService = new TemplateFinderService();
        $this->templateFinderService
            ->setDynamicRouter($this->mDynamicRouter)
            ->setRequestStack($this->mRequestStack)
        ;

        $this->mPage = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Document\PageDocument');

        $this->mRequestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn(new Request())
        ;
    }

    /**
     * @test
     */
    public function find_hasDefault()
    {
        $templateName = 'abc123';

        $this->mPage
            ->expects($this->once())
            ->method('getDefault')
            ->with(AbstractPhpcrDocument::DEFAULT_TEMPLATE_KEY)
            ->willReturn($templateName)
        ;

        $template = $this->templateFinderService->find($this->mPage);
        $this->assertEquals($templateName, $template);
    }

    /**
     * @test
     */
    public function find_systemDefault()
    {
        $mEnhancer1 = $this->getMock('Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface');
        $mEnhancer2 = clone $mEnhancer1;


        $this->mPage
            ->expects($this->once())
            ->method('getDefault')
            ->willReturn(null)
        ;

        $this->mDynamicRouter
            ->expects($this->once())
            ->method('getRouteEnhancers')
            ->willReturn([
                $mEnhancer1,
                $mEnhancer2
            ])
        ;

        $mEnhancer1
            ->expects($this->once())
            ->method('enhance')
            ->with(['_content' => $this->mPage])
            ->willReturn([
                '_blah' => 'blah'
            ])
        ;

        $mEnhancer2
            ->expects($this->once())
            ->method('enhance')
            ->with(['_content' => $this->mPage])
            ->willReturn([
                AbstractPhpcrDocument::DEFAULT_TEMPLATE_KEY => 'def456'
            ])
        ;

        $template = $this->templateFinderService->find($this->mPage);
        $this->assertEquals('def456', $template);
    }

    /**
     * @test
     */
    public function find_noTemplate()
    {
        $this->mPage
            ->expects($this->once())
            ->method('getDefault')
            ->willReturn(null)
        ;

        $this->mDynamicRouter
            ->expects($this->once())
            ->method('getRouteEnhancers')
            ->willReturn([])
        ;

        $template = $this->templateFinderService->find($this->mPage);
        $this->assertNull($template);
    }
}
