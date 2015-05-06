<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\AbstractBlock;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Block extends AbstractBlock
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
     * @PHPCR\Referrers(referringDocument="BlockVariable", referencedBy="block")
     * @var ArrayCollection<BlockVariable>
     **/
	private $variables;

	/**
     * @PHPCR\Referrers(referringDocument="FormBlockMethod", referencedBy="block")
     * @var ArrayCollection<FormBlockMethod>
     **/
	private $formBlockMethods;


	public function __construct()
	{
		$this->variables = new ArrayCollection();
		$this->formBlockMethods = new ArrayCollection();
	}

    public function getType()
    {
        return 'pando_content_bundle.block.dynamic';
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
	 * @return ArrayCollection<BlockVariable>
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * @param BlockVariable $variable
	 * @return $this
	 */
	public function addVariable(BlockVariable $variable)
	{
		$this->variables->add($variable);

		return $this;
	}

	/**
	 * @param BlockVariable $variable
	 * @return $this
	 */
	public function removeVariable(BlockVariable $variable)
	{
		$this->variables->removeElement($variable);

		return $this;
	}

	/**
	 * @return ArrayCollection<FormBlockMethod>
	 */
	public function getFormBlockMethods()
	{
		return $this->formBlockMethods;
	}

	/**
	 * @param FormBlockMethod $formBlockMethod
	 * @return $this
	 */
	public function addFormBlockMethod(FormBlockMethod $formBlockMethod)
	{
		$this->formBlockMethods->add($formBlockMethod);

		return $this;
	}

	/**
	 * @param FormBlockMethod $formBlockMethod
	 * @return $this
	 */
	public function removeFormBlockMethod(FormBlockMethod $formBlockMethod)
	{
		$this->formBlockMethods->removeElement($formBlockMethod);

		return $this;
	}
}
