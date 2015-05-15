<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class FormPageDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\ReferenceOne(targetDocument="FormDocument", strategy="hard")
     * @var FormDocument
     **/
    private $form;

    /**
     * @PHPCR\ReferenceOne(targetDocument="PageDocument", strategy="hard")
     * @var PageDocument
     **/
    private $page;

    /**
     * @PHPCR\ReferenceOne(targetDocument="PageDocument", strategy="hard")
     * @var PageDocument
     **/
	private $successPage;

    /**
     * @PHPCR\ReferenceOne(targetDocument="PageDocument", strategy="hard")
     * @var PageDocument
     **/
	private $failurePage;

    /**
     * @PHPCR\Referrers(referringDocument="MethodDocument", referencedBy="form")
     * @var ArrayCollection<MethodDocument>
     **/
    private $methods;


    public function __construct()
    {
        $this->methods = new ArrayCollection();
    }

    /**
     * @return FormDocument
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param FormDocument $form
     *
     * @return $this
     */
    public function setForm(FormDocument $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return PageDocument
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param PageDocument $page
     *
     * @return $this
     */
    public function setPage(PageDocument $page)
    {
        $this->page = $page;

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
     * @return $this
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
     * @return $this
     */
    public function setFailurePage(PageDocument $failurePage)
    {
        $this->failurePage = $failurePage;

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
