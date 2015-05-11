<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\File;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Javascript extends File
{

}
