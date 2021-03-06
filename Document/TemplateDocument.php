<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(
 *     referenceable=true,
 *     mixins={"mix:lastModified"}
 * )
 */
class TemplateDocument extends AbstractPhpcrDocument
{
	/**
     * @PHPCR\String
     * @var string
     **/
	private $name;

    /**
     * @PHPCR\String
     * @var string
     **/
    private $content;


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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
