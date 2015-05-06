<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

/**
 * @Document(referenceable=true)
 */
class FormBlockMethod implements PrefixInterface, HierarchyInterface
{
    /**
     * @Id(strategy="UUID")
     * @var string
     **/
	private $id;

    /**
     * @ReferenceOne(targetDocument="Form", strategy="hard")
     * @var Form
     **/
	private $form;

    /**
     * @ReferenceOne(targetDocument="Block", strategy="hard")
     * @var Block
     **/
	private $block;

    /**
     * @ReferenceOne(targetDocument="Method", strategy="hard")
     * @var Method
     **/
	private $method;


	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

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
