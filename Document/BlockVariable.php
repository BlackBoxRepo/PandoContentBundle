<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class BlockVariable extends BaseDocument
{
    /**
     * @PHPCR\String
     * @var string
     **/
    private $name;

	/**
     * @PHPCR\ReferenceOne(targetDocument="Block", strategy="hard")
     * @var Block
     **/
	private $block;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Method", strategy="hard")
     * @var Method
     **/
	private $method;


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
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param Block $block
     * @return $this
     */
    public function setBlock(Block $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return Method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param Method $method
     * @return $this
     */
    public function setMethod(Method $method)
    {
        $this->method = $method;

        return $this;
    }
}
