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
     * @PHPCR\ReferenceOne(targetDocument="Page", strategy="hard")
     * @var PageDocument
     **/
	private $successPage;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Page", strategy="hard")
     * @var PageDocument
     **/
	private $failurePage;

    /**
     * @PHPCR\Referrers(referringDocument="FormBlockMethod", referencedBy="form")
     * @var ArrayCollection<FormBlockMethod>
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
     * @return PageDocument
     */
    public function getSuccessPage()
    {
        return $this->successPage;
    }

    /**
     * @param PageDocument $successPage
     *
*@return $this
     */
    public function setSuccessPage(PageDocument $successPage)
    {
        $this->successPage = $successPage;

        return $this;
    }

    /**
     * @return PageDocument
     */
    public function getFailurePage()
    {
        return $this->failurePage;
    }

    /**
     * @param PageDocument $failurePage
     *
*@return $this
     */
    public function setFailurePage(PageDocument $failurePage)
    {
        $this->failurePage = $failurePage;

        return $this;
    }

    /**
     * @return ArrayCollection<FormBlockMethod>
     */
    public function getFormBlockMethods()
    {
        return $this->formBlockMethods;
    }

    /**
     * @param FormBlockMethodDocument $formBlockMethod
     *
*@return $this
     */
    public function addFormBlockMethod(FormBlockMethodDocument $formBlockMethod)
    {
        $this->formBlockMethods->add($formBlockMethod);

        return $this;
    }

    /**
     * @param FormBlockMethodDocument $formBlockMethod
     *
*@return $this
     */
    public function removeFormBlockMethod(FormBlockMethodDocument $formBlockMethod)
    {
        $this->formBlockMethods->removeElement($formBlockMethod);

        return $this;
    }
}
