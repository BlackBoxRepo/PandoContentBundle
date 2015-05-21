<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;

/**
 * @PHPCR\Document(referenceable=true)
 */
class PageDocument extends Page
{
    /**
     * @PHPCR\Id
     * @var string
     **/
	protected $id;

    /**
     * @PHPCR\String
     * @var string
     **/
	protected $name;

	/**
     * @PHPCR\ReferenceMany(targetDocument="BlockDocument", strategy="hard")
     * @var ArrayCollection<BlockDocument>
     **/
	private $blocks;

    /**
     * @PHPCR\ReferenceMany(targetDocument="FormPageDocument", strategy="hard")
     * @var ArrayCollection<FormPageDocument>
     **/
    private $formPages;


	public function __construct()
	{
		$this->blocks    = new ArrayCollection();
        $this->formPages = new ArrayCollection();
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
     *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return ArrayCollection<BlockDocument>
	 */
	public function getBlocks()
	{
		return $this->blocks;
	}

	/**
	 * @param BlockDocument $block
	 *
     * @return $this
	 */
	public function addBlock(BlockDocument $block)
	{
		$this->blocks->add($block);

		return $this;
	}

	/**
	 * @param BlockDocument $block
	 *
     * @return $this
	 */
	public function removeBlock(BlockDocument $block)
	{
		$this->blocks->removeElement($block);

		return $this;
	}

    /**
     * @return ArrayCollection<FormPageDocument>
     */
    public function getFormPages()
    {
        return $this->formPages;
    }

    /**
     * @param FormPageDocument $formPage
     *
     * @return $this
     */
    public function addFormPage(FormPageDocument $formPage)
    {
        $this->formPages->add($formPage);

        return $this;
    }

    /**
     * @param FormPageDocument $formPage
     *
     * @return $this
     */
    public function removeFormPage(FormPageDocument $formPage)
    {
        $this->blocks->removeElement($formPage);

        return $this;
    }

    public function getFormNames()
    {
        return new ArrayCollection();
    }
}
