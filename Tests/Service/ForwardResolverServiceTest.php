<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\FormPageDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Service\ForwardResolverService;
use Doctrine\Common\Collections\ArrayCollection;

class ForwardResolverServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var ForwardResolverService */
    private $forwardResolverService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|PageDocument */
    private $mPageDocument;

    /** @var ArrayCollection<\PHPUnit_Framework_MockObject_MockObject|FormPageDocument> */
    private $formPages;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormPageDocument */
    private $mFormPage1;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormPageDocument */
    private $mFormPage2;

    /** @var FormDocument */
    private $form1;

    /** @var FormDocument */
    private $form2;


    public function setUp()
    {
        $this->forwardResolverService = new ForwardResolverService();

        $this->mPageDocument = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Document\PageDocument');

        $this->form1 = new FormDocument();
        $this->form2 = new FormDocument();

        $this->mFormPage1 = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Document\FormPageDocument');
        $this->mFormPage2 = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Document\FormPageDocument');

        $this->formPages = new ArrayCollection();
        $this->formPages->add($this->mFormPage1);
        $this->formPages->add($this->mFormPage2);
    }

    /**
     * @test
     */
    public function resolve_success()
    {
        $successPage = new PageDocument();

        $this->mPageDocument
            ->expects($this->once())
            ->method('getFormPages')
            ->willReturn($this->formPages)
        ;

        $this->mFormPage1
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($this->form1)
        ;

        $this->mFormPage2
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($this->form2)
        ;

        $this->mFormPage1
            ->expects($this->once())
            ->method('getSuccessPage')
            ->willReturn($successPage)
        ;

        $return = $this->forwardResolverService->resolve($this->mPageDocument, $this->form1, true);
        $this->assertSame($successPage, $return);
    }

    /**
     * @test
     */
    public function resolve_failure()
    {
        $failurePage = new PageDocument();

        $this->mPageDocument
            ->expects($this->once())
            ->method('getFormPages')
            ->willReturn($this->formPages)
        ;

        $this->mFormPage1
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($this->form1)
        ;

        $this->mFormPage2
            ->expects($this->once())
            ->method('getForm')
            ->willReturn($this->form2)
        ;

        $this->mFormPage2
            ->expects($this->once())
            ->method('getFailurePage')
            ->willReturn($failurePage)
        ;

        $return = $this->forwardResolverService->resolve($this->mPageDocument, $this->form2, false);
        $this->assertSame($failurePage, $return);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\NoFormPageException
     */
    public function resolve_noFormPages()
    {
        $this->mPageDocument
            ->expects($this->once())
            ->method('getFormPages')
            ->willReturn(new ArrayCollection())
        ;

        $this->forwardResolverService->resolve($this->mPageDocument, $this->form2, false);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\NoFormPageException
     */
    public function resolve_incorrectFormPage()
    {
        $this->formPages->remove(0);

        $this->mPageDocument
            ->expects($this->once())
            ->method('getFormPages')
            ->willReturn($this->formPages)
        ;

        $this->forwardResolverService->resolve($this->mPageDocument, $this->form1, false);
    }
}
