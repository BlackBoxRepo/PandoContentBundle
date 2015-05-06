<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Document(referenceable=true)
 */
class Method implements PrefixInterface, HierarchyInterface
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
	private $name;

    /**
     * @ReferenceOne(targetDocument="Service", strategy="hard")
     * @var Service
     **/
	private $service;

    /**
     * @Referrers(referringDocument="MethodArgument", referencedBy="method")
     * @var ArrayCollection<MethodArgument>
     **/
	private $arguments;


    public function __construct()
    {
        $this->arguments = new ArrayCollection();
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
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     * @return $this
     */
    public function setService(Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return ArrayCollection<MethodArgument>
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param MethodArgument $argument
     * @return $this
     */
    public function addArgument(MethodArgument $argument)
    {
        $this->arguments->add($argument);

        return $this;
    }

    /**
     * @param MethodArgument $argument
     * @return $this
     */
    public function removeArgument(MethodArgument $argument)
    {
        $this->arguments->removeElement($argument);

        return $this;
    }
}
