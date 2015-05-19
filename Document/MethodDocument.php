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

    /**
     * @PHPCR\Referrers(referringDocument="MethodArgumentDocument", referencedBy="method")
     * @var ArrayCollection<MethodArgumentDocument>
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

    /**
     * @return ArrayCollection<MethodArgumentDocument>
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
     * @param MethodArgumentDocument $argument
     *
     * @return $this
     */
    public function addArgument(MethodArgumentDocument $argument)
    {
        $this->arguments->add($argument);

        return $this;
    }

    /**
     * @param MethodArgumentDocument $argument
     *
     * @return $this
     */
    public function removeArgument(MethodArgumentDocument $argument)
    {
        $this->arguments->removeElement($argument);

        return $this;
    }
}
