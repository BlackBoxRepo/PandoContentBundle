<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Doctrine\ODM\PHPCR\HierarchyInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\PrefixInterface;

abstract class AbstractPhpcrDocument implements PrefixInterface, HierarchyInterface
{
    const DEFAULT_TEMPLATE_KEY = '_template';
    const DEFAULT_CONTROLLER_KEY = '_controller';


    /**
     * @PHPCR\Id
     * @var string
     **/
    private $id;

    /**
     * @var string
     */
    protected $staticPrefix;

    /**
     * @var object
     */
    protected $parentDocument;


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * {@inheritdoc}
     */
    public function setParentDocument($parentDocument)
    {
        $this->parentDocument = $parentDocument;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->getParentDocument();
    }

    /**
     * {@inheritdoc}
     */
    public function setParent($parent)
    {
        $this->setParentDocument($parent);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPrefix($prefix)
    {
        $this->staticPrefix = $prefix;

        return $this;
    }
}
