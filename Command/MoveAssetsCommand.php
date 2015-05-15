<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Command;

use BlackBoxCode\Pando\Bundle\ContentBundle\Factory\AssetFactory;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\FileDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\ImageDocument;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use PHPCR\NodeInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MoveAssetsCommand extends ContainerAwareCommand
{
    /** @var int */
    private static $defaultBatchSize = 100;


    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine_phpcr.odm.default_document_manager');
    }

    /**
     * @return AssetFactory
     */
    public function getAssetFactory()
    {
        return $this->getContainer()->get('asset_factory');
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('pando:assets:move')
            ->setDescription('Moves assets from the web directory to the content repository')
            ->setHelp('If this command quits because PHP exceeded its memory limit please run the command again with the --no-debug option')
            ->addOption(
                'batchSize',
                'b',
                InputArgument::OPTIONAL,
                'Batch size?',
                self::$defaultBatchSize
            )
            ->addOption(
                'clear',
                null,
                InputOption::VALUE_NONE,
                'Clears assets from the content repository'
            )
        ;
    }

    /**
     * Moves assets into the content repository
     *
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $assetsDirectory = str_replace('/', DIRECTORY_SEPARATOR, 'web/bundles');

        $manager = $this->getDocumentManager();
        $assetFactory = $this->getAssetFactory();

        if ($input->getOption('clear')) {
            $output->writeln('Clearing assets from the content repository. This should NEVER be used in a production environment unless you know what you\'re doing!');

            if ($assetNode = $manager->find(null, '/asset')) {
                $manager->remove($assetNode);
                $manager->flush();
                $manager->clear();
            }
        }

        $batchSize = (int) $input->getOption('batchSize') ?: self::$defaultBatchSize;
        $assetFileArray = $this->getAssetFileArray($assetsDirectory);
        if (count($assetFileArray) === 0) {
            $output->writeln('No assets to move, please run the assets:install command first.');
            return 1; // failure
        }

        $output->writeln('Moving assets into content repository...');
        $progress = new ProgressBar($output, count($assetFileArray));
        $progress->setFormat('verbose');
        $progress->start();

        foreach (array_chunk($assetFileArray, $batchSize) as $chunk) {
            foreach ($chunk as $fileArr) {
                /** @var \SplFileInfo $file */
                $file = $fileArr['file'];
                $linkedLocation = $fileArr['linkedLocation'];

                $parentPath = $file->getPath();
                if ($linkedLocation) {
                    $needle = DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
                    $pos = strpos($parentPath, $needle);
                    $parentPath = $linkedLocation . DIRECTORY_SEPARATOR . substr($parentPath, $pos + strlen($needle));
                }
                $parentPath = str_replace($assetsDirectory . DIRECTORY_SEPARATOR, '', $parentPath);
                $parentPath = str_replace('\\', '/', $parentPath);
                $parentNode = $this->findOrCreateNode('asset/' . $parentPath);

                /** @var FileDocument|ImageDocument $asset */
                $asset = $assetFactory->create($file);
                $asset
                    ->copyContentFromFile($file)
                    ->setParentDocument($parentNode)
                    ->setNodename($file->getBasename())
                ;

                $manager->persist($asset);
                $progress->advance();
            }

            $manager->flush();
            $manager->clear();
        }

        $this->deleteDirectoryContents($assetsDirectory);
        $progress->finish();
        $output->writeln("\nFinished!");

        return 0; // everything went okay
    }

    /**
     * Finds or creates a node
     *
     * @param string $path
     * @return PHPCR\NodeInterface
     */
    public function findOrCreateNode($path)
    {
        if ($node = $this->getDocumentManager()->find(null, $path)) {
            return $node;
        }

        NodeHelper::createPath($this->getDocumentManager()->getPhpcrSession(), $path);

        return $this->findOrCreateNode($path);
    }

    /**
     * Returns an array of SplFileInfo objects and their
     * linked location (if applicable) found recursively in the given path
     *
     * @param string $path
     * @param string|null $linkedLocation
     * @return array
     */
    public function getAssetFileArray($path, $linkedLocation = null)
    {
        $assets = [];

        $dir = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($dir);

        /** @var \SplFileInfo $object */
        foreach ($iterator as $object) {
            if ($object->isLink()) {
                $linkedLocation = $object->getPathname();
                $assets = array_merge($assets, $this->getAssetFileArray($object->getRealPath(), $linkedLocation));
            } else if ($object->isFile() && strpos($object->getPathname(), DIRECTORY_SEPARATOR . '.') === false) {
                $assets[] = [
                    'linkedLocation' => $linkedLocation,
                    'file' => $object
                ];
            }
        }

        return $assets;
    }

    /**
     * Deletes a directories contents
     *
     * @param string $path
     */
    public function deleteDirectoryContents($path)
    {
        $dir = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::CHILD_FIRST);

        /** @var \SplFileInfo $object */
        foreach ($iterator as $object) {
            if (!in_array($object->getBasename(), ['.', '..'])) {
                if ($object->isLink() || $object->isFile()) {
                    unlink($object->getPathname());
                } else if ($object->isDir()) {
                    rmdir($object->getPathname());
                }
            }
        }
    }
}
