<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class FormBlockMethodArgumentDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\ReferenceOne(targetDocument="FormDocument", strategy="hard")
     * @var FormDocument
     **/
	private $form;

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
	public function setForm($form)
	{
		$this->form = $form;

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
