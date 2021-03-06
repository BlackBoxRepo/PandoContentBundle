<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\File;

/**
 * @PHPCR\Document(referenceable=true)
 */
class FileDocument extends File
{
    /**
     * @PHPCR\Uuid
     * @var string
     */
    protected $uuid;


    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}
