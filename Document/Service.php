<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Document(referenceable=true)
 */
class Service implements PrefixInterface, HierarchyInterface
{
    /**
     * @Id(strategy="UUID")
     * @var string
     **/
	private $id;

    /**
     * @String
     * @var string
     **/
	private $serviceName;

    /**
     * @String
     * @var string
     **/
	private $className;

    /**
     * @Referrers(referringDocument="Method", referencedBy="service")
     * @var ArrayCollection<Method>
     **/
	private $methods;


    public function __construct()
    {
        $this->methods = new ArrayCollection();
    }

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
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
	 * @return $this
	 */
	public function setClassName($className)
	{
		$this->className = $className;

		return $this;
	}

    /**
     * @return ArrayCollection<Method>
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param Method $method
     * @return $this
     */
    public function addMethod(Method $method)
    {
        $this->methods->add($method);

        return $this;
    }

    /**
     * @param Method $method
     * @return $this
     */
    public function removeMethod(Method $method)
    {
        $this->methods->removeElement($method);

        return $this;
    }
}
