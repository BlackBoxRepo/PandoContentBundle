<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class BlockVariableDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\String
     * @var string
     **/
    private $name;

	/**
     * @PHPCR\ReferenceOne(targetDocument="Block", strategy="hard")
     * @var BlockDocument
     **/
	private $block;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Method", strategy="hard")
     * @var MethodDocument
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
     *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

    /**
     * @return BlockDocument
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param BlockDocument $block
     *
     * @return $this
     */
    public function setBlock(BlockDocument $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return MethodDocument
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param MethodDocument $method
     *
     * @return $this
     */
    public function setMethod(MethodDocument $method)
    {
        $this->method = $method;

        return $this;
    }
}
