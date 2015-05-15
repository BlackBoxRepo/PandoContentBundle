<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\FileDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\ImageDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\JavascriptDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\StylesheetDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Factory\AssetFactory;

class AssetFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|AssetFactory */
    private $mAssetFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject|\SplFileInfo */
    private $mSplFileInfo;


    public function setUp()
    {
        $this->mAssetFactory = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Factory\AssetFactory', ['getMimeType']);
        $this->mSplFileInfo = $this
            ->getMockBuilder('\SplFileInfo')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * @test
     */
    public function create_image()
    {
        $this->mSplFileInfo
            ->expects($this->once())
            ->method('getExtension')
            ->willReturn('png')
        ;

        $this->mAssetFactory
            ->expects($this->once())
            ->method('getMimeType')
            ->willReturn('image/png')
        ;

        $return = $this->mAssetFactory->create($this->mSplFileInfo);
        $this->assertInstanceOf(get_class(new ImageDocument()), $return);
    }

    /**
     * @test
     */
    public function create_stylesheet()
    {
        $this->mSplFileInfo
            ->expects($this->once())
            ->method('getExtension')
            ->willReturn('css')
        ;

        $return = $this->mAssetFactory->create($this->mSplFileInfo);
        $this->assertInstanceOf(get_class(new StylesheetDocument()), $return);
    }

    /**
     * @test
     */
    public function create_javascript()
    {
        $this->mSplFileInfo
            ->expects($this->once())
            ->method('getExtension')
            ->willReturn('js')
        ;

        $return = $this->mAssetFactory->create($this->mSplFileInfo);
        $this->assertInstanceOf(get_class(new JavascriptDocument()), $return);
    }

    /**
     * @test
     */
    public function create_other()
    {
        $this->mSplFileInfo
            ->expects($this->once())
            ->method('getExtension')
            ->willReturn('somethingelse')
        ;

        $this->mAssetFactory
            ->expects($this->once())
            ->method('getMimeType')
            ->willReturn('something/else')
        ;

        $return = $this->mAssetFactory->create($this->mSplFileInfo);
        $this->assertInstanceOf(get_class(new FileDocument()), $return);
    }
}
