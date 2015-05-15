<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class FormDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\String
     * @var string
     **/
    private $name;

    /**
     * @PHPCR\Referrers(referringDocument="FormBlockMethodDocument", referencedBy="form")
     * @var ArrayCollection<FormBlockMethodDocument>
     **/
    private $formBlockMethods;


    public function __construct()
    {
        $this->formBlockMethods = new ArrayCollection();
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
     * @return ArrayCollection<FormBlockMethodDocument>
     */
    public function getFormBlockMethods()
    {
        return $this->formBlockMethods;
    }

    /**
     * @param FormBlockMethodDocument $formBlockMethod
     *
     * @return $this
     */
    public function addFormBlockMethod(FormBlockMethodDocument $formBlockMethod)
    {
        $this->formBlockMethods->add($formBlockMethod);

        return $this;
    }

    /**
     * @param FormBlockMethodDocument $formBlockMethod
     *
     * @return $this
     */
    public function removeFormBlockMethod(FormBlockMethodDocument $formBlockMethod)
    {
        $this->formBlockMethods->removeElement($formBlockMethod);

        return $this;
    }
}
