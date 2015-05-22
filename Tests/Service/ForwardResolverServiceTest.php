<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormPageDocument;
use BlackBoxCode\Pando\ContentBundle\Service\ForwardResolverService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;

class ForwardResolverServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var ForwardResolverService */
    private $forwardResolverService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|PageDocument */
    private $mPageDocument;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Form */
    private $mForm;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormPageDocument */
    private $mFormPage;


    public function setUp()
    {
        $this->forwardResolverService = new ForwardResolverService();

        $this->mPageDocument = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\PageDocument');

        $this->mForm = $this
            ->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mFormPage = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\FormPageDocument');
    }

    /**
     * @test
     */
    public function resolve_success()
    {
        $formName = 'the_form';
        $successPage = new PageDocument();

        $this->mForm
            ->expects($this->once())
            ->method('getName')
            ->willReturn($formName)
        ;

        $this->mPageDocument
            ->expects($this->once())
            ->method('getFormPageByFormName')
            ->with($formName)
            ->willReturn($this->mFormPage)
        ;

        $this->mFormPage
            ->expects($this->once())
            ->method('getSuccessPage')
            ->willReturn($successPage)
        ;

        $return = $this->forwardResolverService->resolve($this->mPageDocument, $this->mForm, true);
        $this->assertSame($successPage, $return);
    }

    /**
     * @test
     */
    public function resolve_failure()
    {
        $formName = 'the_form';
        $failurePage = new PageDocument();

        $this->mForm
            ->expects($this->once())
            ->method('getName')
            ->willReturn($formName)
        ;

        $this->mPageDocument
            ->expects($this->once())
            ->method('getFormPageByFormName')
            ->with($formName)
            ->willReturn($this->mFormPage)
        ;

        $this->mFormPage
            ->expects($this->once())
            ->method('getFailurePage')
            ->willReturn($failurePage)
        ;

        $return = $this->forwardResolverService->resolve($this->mPageDocument, $this->mForm, false);
        $this->assertSame($failurePage, $return);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\NoFormPageException
     */
    public function resolve_noFormPages()
    {
        $formName = 'the_form';

        $this->mForm
            ->expects($this->once())
            ->method('getName')
            ->willReturn($formName)
        ;

        $this->mPageDocument
            ->expects($this->once())
            ->method('getFormPageByFormName')
            ->with($formName)
            ->willReturn(null)
        ;

        $this->forwardResolverService->resolve($this->mPageDocument, $this->mForm, false);
    }
}
