<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class FormBlockMethodDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\ReferenceOne(targetDocument="Form", strategy="hard")
     * @var FormDocument
     **/
	private $form;

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
