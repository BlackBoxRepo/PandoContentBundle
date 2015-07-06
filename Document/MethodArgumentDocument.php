<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class MethodArgumentDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\ReferenceOne(targetDocument="MethodDocument", strategy="hard")
     * @var MethodDocument
     **/
	private $method;

    /**
     * @PHPCR\ReferenceMany(targetDocument="ArgumentDocument", strategy="hard")
     * @var ArrayCollection<ArgumentDocument>
     **/
	private $arguments;

    public function __construct()
    {
        $this->arguments = new ArrayCollection();
    }

    /**
     * @return MethodDocument
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param MethodDocument $method
     *
     * @return $this
     */
    public function setMethod(MethodDocument $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param ArrayCollection $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @param ArgumentDocument $argument
     */
    public function addArgument(ArgumentDocument $argument)
    {
        $this->getArguments()->add($argument);
    }
}
