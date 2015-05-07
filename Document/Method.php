<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Method extends BaseDocument
{
	/**
     * @PHPCR\String
     * @var string
     **/
	private $name;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Service", strategy="hard")
     * @var Service
     **/
	private $service;

    /**
     * @PHPCR\Referrers(referringDocument="MethodArgument", referencedBy="method")
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
        $iterator = $this->arguments->getIterator();

        $iterator->uasort(
            function ($a, $b) {
                return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
            }
        );

        return new ArrayCollection(array_values(iterator_to_array($iterator)));
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
