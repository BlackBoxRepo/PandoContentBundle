<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\BlockVariableDocument;
use BlackBoxCode\Pando\ContentBundle\Document\TemplateDocument;
use BlackBoxCode\Pando\ContentBundle\Service\BlockVariableService;
use BlackBoxCode\Pando\ContentBundle\Service\FormContainerService;
use BlackBoxCode\Pando\ContentBundle\Service\FormService;
use BlackBoxCode\Pando\ContentBundle\Service\DynamicBlockService;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class DynamicBlockServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var DynamicBlockService */
    private $dynamicBlockService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|EngineInterface */
    private $mTemplating;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormService */
    private $mFormService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|BlockVariableService */
    private $mBlockVariableService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormContainerService */
    private $mFormContainerService;


    public function setUp()
    {
        $this->mTemplating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->mFormService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormService');
        $this->mBlockVariableService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\BlockVariableService');
        $this->mFormContainerService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormContainerService');

        $this->dynamicBlockService = new DynamicBlockService();
        $this->dynamicBlockService
            ->setTemplating($this->mTemplating)
            ->setFormService($this->mFormService)
            ->setBlockVariableService($this->mBlockVariableService)
            ->setFormContainerService($this->mFormContainerService)
        ;
    }

    /**
     * @test
     */
    public function execute()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|TemplateDocument $template */
        $mTemplate = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\TemplateDocument');

        /** @var \PHPUnit_Framework_MockObject_MockObject|BlockDocument $block */
        $mBlock = $this->getMock('BlackBoxCode\Pando\ContentBundle\Document\BlockDocument');

        /** @var \PHPUnit_Framework_MockObject_MockObject|BlockContextInterface $mBlockContext */
        $mBlockContext = $this->getMock('Sonata\BlockBundle\Block\BlockContextInterface');

        $this->mFormService
            ->expects($this->once())
            ->method('processBlock')
            ->willReturn(true)
        ;

        $mBlockContext
            ->expects($this->once())
            ->method('getBlock')
            ->willReturn($mBlock)
        ;

        $mBlock
            ->expects($this->once())
            ->method('getTemplate')
            ->willReturn($mTemplate)
        ;

        $templateName = 'im_a_template';

        $mTemplate
            ->expects($this->once())
            ->method('getName')
            ->willReturn($templateName)
        ;

        $blockVariables = new ArrayCollection([
            new BlockVariableDocument(),
            new BlockVariableDocument()
        ]);

        $mBlock
            ->expects($this->once())
            ->method('getVariables')
            ->willReturn($blockVariables)
        ;

        $response = new Response();

        $this->mTemplating
            ->expects($this->once())
            ->method('renderResponse')
            ->with($templateName, $blockVariables->toArray())
            ->willReturn($response)
        ;

        $return = $this->dynamicBlockService->execute($mBlockContext);
        $this->assertSame($response, $return);
    }

    /**
     * @test
     */
    public function load()
    {
        $block = new BlockDocument();

        $this->mBlockVariableService
            ->expects($this->once())
            ->method('setBlock')
            ->with($block)
            ->willReturn($this->mBlockVariableService)
        ;

        $this->mBlockVariableService
            ->expects($this->once())
            ->method('populateBlockVariables')
        ;

        $this->dynamicBlockService->load($block);
    }
}
