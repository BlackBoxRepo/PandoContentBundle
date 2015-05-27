<?php
namespace BlackBoxCode\Pando\ContentBundle\EventListener;

use BlackBoxCode\Pando\ContentBundle\Service\UniqueDocumentValidatorService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ODM\PHPCR\DocumentRepository;

class UniqueDocumentSubscriber implements EventSubscriber
{
    /** @var UniqueDocumentValidatorService */
    private $uniqueDocumentValidatorService;


    /**
     * @param UniqueDocumentValidatorService $uniqueDocumentValidatorService
     *
     * @return $this
     */
    public function setUniqueDocumentValidatorService(UniqueDocumentValidatorService $uniqueDocumentValidatorService)
    {
        $this->uniqueDocumentValidatorService = $uniqueDocumentValidatorService;

        return $this;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate'
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->validate($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->validate($args);
    }

    private function validate(LifecycleEventArgs $args)
    {
        /** @var DocumentRepository $repository */
        $repository = $args->getObjectManager()->getRepository(get_class($args->getObject()));
        $this->uniqueDocumentValidatorService->validate($repository, $args->getObject());
    }
}
