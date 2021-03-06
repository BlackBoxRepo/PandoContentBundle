<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\AbstractBlock;

/**
 * @PHPCR\Document(referenceable=true)
 */
class BlockDocument extends AbstractBlock
{
    /**
     * @PHPCR\Uuid
     * @var string
     */
    protected $uuid;

	/**
     * @PHPCR\Referrers(referringDocument="BlockVariableDocument", referencedBy="block")
     * @var ArrayCollection<BlockVariableDocument>
     **/
	private $variables;

	/**
     * @PHPCR\Referrers(referringDocument="FormBlockMethodArgumentDocument", referencedBy="block")
     * @var ArrayCollection<FormBlockMethodArgumentDocument>
     **/
	private $formBlockMethods;

    /**
     * @PHPCR\ReferenceOne(targetDocument="TemplateDocument", strategy="hard")
     * @var TemplateDocument
     */
    private $template;

    /**
     * @var ArrayCollection
     */
    private $viewVariables;


	public function __construct()
	{
		$this->variables = new ArrayCollection();
		$this->formBlockMethods = new ArrayCollection();
        $this->viewVariables = new ArrayCollection();
	}

    public function getType()
    {
        return 'black_box_code.pando.content_bundle.service.dynamic_block_service';
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
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
	 * @return ArrayCollection<BlockVariableDocument>
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * @param BlockVariableDocument $variable
	 *
     * @return $this
	 */
	public function addVariable(BlockVariableDocument $variable)
	{
		$this->variables->add($variable);

		return $this;
	}

	/**
	 * @param BlockVariableDocument $variable
	 *
     * @return $this
	 */
	public function removeVariable(BlockVariableDocument $variable)
	{
		$this->variables->removeElement($variable);

		return $this;
	}

	/**
	 * @return ArrayCollection<FormBlockMethodArgumentDocument>
	 */
	public function getFormBlockMethods()
	{
		return $this->formBlockMethods;
	}

	/**
	 * @param FormBlockMethodArgumentDocument $formBlockMethod
     *
	 * @return $this
	 */
	public function addFormBlockMethod(FormBlockMethodArgumentDocument $formBlockMethod)
	{
		$this->formBlockMethods->add($formBlockMethod);

		return $this;
	}

	/**
	 * @param FormBlockMethodArgumentDocument $formBlockMethod
     *
	 * @return $this
	 */
	public function removeFormBlockMethod(FormBlockMethodArgumentDocument $formBlockMethod)
	{
		$this->formBlockMethods->removeElement($formBlockMethod);

		return $this;
	}

    /**
     * @return TemplateDocument
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param TemplateDocument $template
     *
     * @return $this
     */
    public function setTemplate(TemplateDocument $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getViewVariables()
    {
        if (is_null($this->viewVariables)) {
            $this->viewVariables = new ArrayCollection();
        }
        return $this->viewVariables;
    }

    /**
     * @param ArrayCollection $viewVariables
     *
     * @return $this
     */
    public function setViewVariables(ArrayCollection $viewVariables)
    {
        $this->viewVariables = $viewVariables;

        return $this;
    }
}
