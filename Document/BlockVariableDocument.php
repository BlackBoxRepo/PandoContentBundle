<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

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
     * @PHPCR\ReferenceOne(targetDocument="BlockDocument", strategy="hard")
     * @var BlockDocument
     **/
	private $block;

    /**
     * @PHPCR\ReferenceOne(targetDocument="MethodArgumentDocument", strategy="hard")
     * @var MethodArgumentDocument
     **/
	private $methodArgument;


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
    public function getMethodArgument()
    {
        return $this->methodArgument;
    }

    /**
     * @param MethodDocument $methodArgument
     *
     * @return $this
     */
    public function setMethodArgument(MethodDocument $methodArgument)
    {
        $this->methodArgument = $methodArgument;

        return $this;
    }
}
