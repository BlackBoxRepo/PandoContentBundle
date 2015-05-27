<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Annotation\UniqueDocument;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\DocumentNotUniqueException;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ODM\PHPCR\DocumentRepository;

class UniqueDocumentValidatorService
{
    /** @var Reader */
    private $reader;


    /**
     * @param Reader $reader
     *
     * @return $this
     */
    public function setReader(Reader $reader)
    {
        $this->reader = $reader;

        return $this;
    }

    /**
     * @param DocumentRepository $repository
     * @param $document
     *
     * @return bool
     */
    public function validate(DocumentRepository $repository, $document)
    {
        $r = new \ReflectionObject($document);

        foreach ($this->reader->getClassAnnotations($r) as $annotation) {
            if ($annotation instanceof UniqueDocument) {
                /** @var UniqueDocument $annotation */
                $fields = array_flip($annotation->getFields());
                if (count($fields) > 0) {
                    $qb = $repository->createQueryBuilder('d');

                    foreach ($fields as $k => $v) {
                        $method = 'get' . ucwords($k);
                        $value = $document->$method();

                        if (is_object($value)) {
                            $class = get_class($value);

                            $qb
                                ->addJoinInner()
                                    ->right()->document($class, $class)->end()
                                        ->condition()->equi('d.' . $k, $class . '.uuid');

                            $qb->andWhere()->eq()->field($class . '.uuid')->literal($value->getUuid());;
                        } else {
                            $qb->andWhere()->eq()->field('d.' . $k)->literal($value);
                        }
                    }

                    $result = $qb->getQuery()->getResult();
                    if (count($result) > 0) {
                        throw new DocumentNotUniqueException(sprintf(
                            "Unique constraint violation: Duplicate '%s' entry on field(s) {%s}",
                            get_class($document),
                            implode(', ', array_keys($fields))
                        ));
                    }
                }
            }
        }
    }
}
