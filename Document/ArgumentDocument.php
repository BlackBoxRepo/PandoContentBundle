<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class ArgumentDocument extends AbstractPhpcrDocument
{
    /**
     * @PHPCR\Long
     * @var integer
     **/
	private $order;

    /**
     * @PHPCR\String
     * @var string
     **/
    private $value;

    /**
     * @PHPCR\ReferenceOne(targetDocument="MethodArgumentDocument", strategy="hard")
     * @var MethodDocument
     **/
	private $methodArgument;

    /**
     * @PHPCR\ReferenceOne(targetDocument="MethodDocument", strategy="hard")
     * @var MethodDocument
     **/
	private $callback;


    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

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

    /**
     * @return MethodArgumentDocument
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param MethodArgumentDocument $callback
     *
     * @return $this
     */
    public function setCallback(MethodArgumentDocument $callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
