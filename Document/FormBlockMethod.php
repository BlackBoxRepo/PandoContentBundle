<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class FormBlockMethod extends BaseDocument
{
    /**
     * @PHPCR\ReferenceOne(targetDocument="Form", strategy="hard")
     * @var Form
     **/
	private $form;

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
	 * @return Form
	 */
	public function getForm()
	{
		return $this->form;
	}

	/**
	 * @param Form $form
	 * @return $this
	 */
	public function setForm($form)
	{
		$this->form = $form;

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
