<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image as BaseImage;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Image extends BaseImage
{

}
