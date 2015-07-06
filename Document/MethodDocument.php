<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class MethodDocument extends AbstractPhpcrDocument
{
	/**
     * @PHPCR\String
     * @var string
     **/
	private $name;

    /**
     * @PHPCR\ReferenceOne(targetDocument="ServiceDocument", strategy="hard")
     * @var ServiceDocument
     **/
	private $service;

    public function __construct()
    {
        $this->arguments = new ArrayCollection();
    }

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
     *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

    /**
     * @return ServiceDocument
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param ServiceDocument $service
     *
     * @return $this
     */
    public function setService(ServiceDocument $service)
    {
        $this->service = $service;

        return $this;
    }
}
