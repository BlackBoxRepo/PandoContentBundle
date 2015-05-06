<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Form extends BaseDocument
{
    /**
     * @PHPCR\String
     * @var string
     **/
    private $name;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Page", strategy="hard")
     * @var Page
     **/
	private $successPage;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Page", strategy="hard")
     * @var Page
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
     * @return Page
     */
    public function getSuccessPage()
    {
        return $this->successPage;
    }

    /**
     * @param Page $successPage
     * @return $this
     */
    public function setSuccessPage(Page $successPage)
    {
        $this->successPage = $successPage;

        return $this;
    }

    /**
     * @return Page
     */
    public function getFailurePage()
    {
        return $this->failurePage;
    }

    /**
     * @param Page $failurePage
     * @return $this
     */
    public function setFailurePage(Page $failurePage)
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
     * @param FormBlockMethod $formBlockMethod
     * @return $this
     */
    public function addFormBlockMethod(FormBlockMethod $formBlockMethod)
    {
        $this->formBlockMethods->add($formBlockMethod);

        return $this;
    }

    /**
     * @param FormBlockMethod $formBlockMethod
     * @return $this
     */
    public function removeFormBlockMethod(FormBlockMethod $formBlockMethod)
    {
        $this->formBlockMethods->removeElement($formBlockMethod);

        return $this;
    }
}
