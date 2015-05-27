<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image as BaseImage;

/**
 * @PHPCR\Document(referenceable=true)
 */
class ImageDocument extends BaseImage
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
