<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Command\MoveAssetsCommand;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\File;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Image;
use BlackBoxCode\Pando\Bundle\ContentBundle\Factory\AssetFactory;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Component\Console\Tester\CommandTester;

class MoveAssetsCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|MoveAssetsCommand */
    private $mMoveAssetsCommand;

    /** @var CommandTester */
    private $commandTester;

    /** @var \PHPUnit_Framework_MockObject_MockObject|DocumentManager */
    private $mDocumentManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject|AssetFactory */
    private $mAssetFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject|\SplFileInfo */
    private $mSplFileInfo;


    public function setUp()
    {
        $this->mMoveAssetsCommand = $this
            ->getMockBuilder('BlackBoxCode\Pando\Bundle\ContentBundle\Command\MoveAssetsCommand')
            ->setMethods([
                'getDocumentManager',
                'getAssetFactory',
                'findOrCreateNode',
                'getAssetFileArray',
                'deleteDirectoryContents'
            ])
            ->getMock()
        ;

        $this->commandTester = new CommandTester($this->mMoveAssetsCommand);

        $this->mDocumentManager = $this
            ->getMockBuilder('Doctrine\ODM\PHPCR\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mAssetFactory = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Factory\AssetFactory');

        $this->mSplFileInfo = $this
            ->getMockBuilder('\SplFileInfo')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mMoveAssetsCommand
            ->expects($this->once())
            ->method('getDocumentManager')
            ->willReturn($this->mDocumentManager)
        ;

        $this->mMoveAssetsCommand
            ->expects($this->once())
            ->method('getAssetFactory')
            ->willReturn($this->mAssetFactory)
        ;
    }

    /**
     * @test
     */
    public function execute_batch()
    {
        $assetsDir = str_replace('/', DIRECTORY_SEPARATOR, 'web/bundles');

        $mSplFileInfo1 = clone $this->mSplFileInfo;
        $mSplFileInfo2 = clone $this->mSplFileInfo;
        $mSplFileInfo3 = clone $this->mSplFileInfo;

        /** @var \PHPUnit_Framework_MockObject_MockObject|File $mAsset */
        $mAsset = $this->getMock('BlackBoxCode\Pando\Bundle\ContentBundle\Document\File');

        $this->mMoveAssetsCommand
            ->expects($this->once())
            ->method('getAssetFileArray')
            ->with($assetsDir)
            ->willReturn([
                ['file' => $mSplFileInfo1, 'linkedLocation' => null],
                ['file' => $mSplFileInfo2, 'linkedLocation' => 'llama-bundle'],
                ['file' => $mSplFileInfo3, 'linkedLocation' => null]
            ])
        ;

        $mSplFileInfo1
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('web/bundles/content-bundle/pdf')
        ;

        $mSplFileInfo2
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('llama-bundle/public/pdf')
        ;

        $mSplFileInfo3
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('web/bundles/content-bundle/pdf')
        ;

        $this->mMoveAssetsCommand
            ->expects($this->exactly(3))
            ->method('findOrCreateNode')
            ->withConsecutive(
                ['asset/content-bundle/pdf'],
                ['asset/llama-bundle/pdf'],
                ['asset/content-bundle/pdf']
            )
            ->willReturn(new Generic())
        ;

        $this->mAssetFactory
            ->expects($this->exactly(3))
            ->method('create')
            ->with($this->isInstanceOf('\SplFileInfo'))
            ->willReturn($mAsset)
        ;

        $mAsset
            ->expects($this->exactly(3))
            ->method('copyContentFromFile')
            ->with($this->isInstanceOf('\SplFileInfo'))
            ->willReturn($mAsset)
        ;

        $mAsset
            ->expects($this->exactly(3))
            ->method('setParentDocument')
            ->with($this->isInstanceOf('Doctrine\ODM\PHPCR\Document\Generic'))
            ->willReturn($mAsset)
        ;

        $mSplFileInfo1
            ->expects($this->once())
            ->method('getBasename')
            ->willReturn('1.pdf')
        ;

        $mSplFileInfo2
            ->expects($this->once())
            ->method('getBasename')
            ->willReturn('2.pdf')
        ;

        $mSplFileInfo3
            ->expects($this->once())
            ->method('getBasename')
            ->willReturn('3.pdf')
        ;

        $mAsset
            ->expects($this->exactly(3))
            ->method('setNodeName')
            ->withConsecutive(
                ['1.pdf'],
                ['2.pdf'],
                ['3.pdf']
            )
            ->willReturn($mAsset)
        ;

        $this->mDocumentManager
            ->expects($this->exactly(3))
            ->method('persist')
            ->with($mAsset)
        ;

        $this->mDocumentManager
            ->expects($this->exactly(2))
            ->method('flush')
        ;

        $this->mDocumentManager
            ->expects($this->exactly(2))
            ->method('clear')
        ;

        $this->mMoveAssetsCommand
            ->expects($this->once())
            ->method('deleteDirectoryContents')
            ->with($assetsDir)
        ;

        $returnCode = $this->commandTester->execute(['--batchSize' => 2]);
        $this->assertEquals(0, $returnCode);
    }

    /**
     * @test
     */
    public function execute_clear()
    {
        $this->mDocumentManager
            ->expects($this->exactly(1))
            ->method('find')
            ->with(null, '/asset')
            ->willReturn(new Generic())
        ;

        $this->mDocumentManager
            ->expects($this->exactly(1))
            ->method('remove')
            ->with($this->isInstanceOf('Doctrine\ODM\PHPCR\Document\Generic'))
        ;

        $this->mDocumentManager
            ->expects($this->exactly(1))
            ->method('flush')
        ;

        $this->mDocumentManager
            ->expects($this->exactly(1))
            ->method('clear')
        ;

        $this->commandTester->execute(['--clear' => true]);
    }

    /**
     * @test
     */
    public function execute_noAssets()
    {
        $assetsDir = str_replace('/', DIRECTORY_SEPARATOR, 'web/bundles');

        $this->mMoveAssetsCommand
            ->expects($this->once())
            ->method('getAssetFileArray')
            ->with($assetsDir)
            ->willReturn([])
        ;

        $returnCode = $this->commandTester->execute([]);
        $this->assertEquals(1, $returnCode);
    }
}
