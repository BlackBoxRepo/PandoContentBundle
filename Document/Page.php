<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page as BasePage;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Page extends BasePage
{
    /**
     * @PHPCR\Id
     * @var string
     **/
	private $id;

    /**
     * @PHPCR\String
     * @var string
     **/
	private $name;

	/**
     * @PHPCR\ReferenceMany(targetDocument="Block", strategy="hard")
     * @var ArrayCollection<Block>
     **/
	private $blocks;


	public function __construct()
	{
		$this->blocks = new ArrayCollection();
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
	 * @return ArrayCollection<Block>
	 */
	public function getBlocks()
	{
		return $this->blocks;
	}

	/**
	 * @param Block $block
	 * @return $this
	 */
	public function addBlock(Block $block)
	{
		$this->blocks->add($block);

		return $this;
	}

	/**
	 * @param Block $block
	 * @return $this
	 */
	public function removeBlock(Block $block)
	{
		$this->blocks->removeElement($block);

		return $this;
	}
}
