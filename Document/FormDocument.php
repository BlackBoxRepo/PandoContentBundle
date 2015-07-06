<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

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
     * @PHPCR\Referrers(referringDocument="FormBlockMethodArgumentDocument", referencedBy="form")
     * @var ArrayCollection<FormBlockMethodArgumentDocument>
     **/
    private $formBlockMethodArguments;


    public function __construct()
    {
        $this->formBlockMethodArguments = new ArrayCollection();
        $this->submitted = false;
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
     * @return ArrayCollection<FormBlockMethodArgumentDocument>
     */
    public function getFormBlockMethodArguments()
    {
        return $this->formBlockMethodArguments;
    }

    /**
     * @param FormBlockMethodArgumentDocument $formBlockMethod
     *
     * @return $this
     */
    public function addFormBlockMethod(FormBlockMethodArgumentDocument $formBlockMethod)
    {
        $this->formBlockMethodArguments->add($formBlockMethod);

        return $this;
    }

    /**
     * @param FormBlockMethodArgumentDocument $formBlockMethod
     *
     * @return $this
     */
    public function removeFormBlockMethod(FormBlockMethodArgumentDocument $formBlockMethod)
    {
        $this->formBlockMethodArguments->removeElement($formBlockMethod);

        return $this;
    }
}
