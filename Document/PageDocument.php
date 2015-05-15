<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

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
     *
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
}
