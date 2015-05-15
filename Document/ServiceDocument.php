<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class ServiceDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\String
     * @var string
     **/
	private $serviceName;

    /**
     * @PHPCR\String
     * @var string
     **/
	private $className;

    /**
     * @PHPCR\Referrers(referringDocument="MethodDocument", referencedBy="service")
     * @var ArrayCollection<MethodDocument>
     **/
	private $methods;


    public function __construct()
    {
        $this->methods = new ArrayCollection();
    }

	/**
	 * @return string
	 */
	public function getServiceName()
	{
		return $this->serviceName;
	}

	/**
	 * @param string $serviceName
     *
	 * @return $this
	 */
	public function setServiceName($serviceName)
	{
		$this->serviceName = $serviceName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->className;
	}

	/**
	 * @param string $className
     *
	 * @return $this
	 */
	public function setClassName($className)
	{
		$this->className = $className;

		return $this;
	}

    /**
     * @return ArrayCollection<MethodDocument>
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param MethodDocument $method
     *
     * @return $this
     */
    public function addMethod(MethodDocument $method)
    {
        $this->methods->add($method);

        return $this;
    }

    /**
     * @param MethodDocument $method
     *
     * @return $this
     */
    public function removeMethod(MethodDocument $method)
    {
        $this->methods->removeElement($method);

        return $this;
    }
}
