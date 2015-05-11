<?php
namespace BlackBoxCode\Pando\Bundle\BaseBundle\Command;

use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveAssetsCommand extends ContainerAwareCommand
{
    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine_phpcr.odm.default_document_manager');
    }

    public function configure()
    {
        $this
            ->setName('pando:move:assets')
            ->setDescription('Moves assets from the web directory to the content repository')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }
}

