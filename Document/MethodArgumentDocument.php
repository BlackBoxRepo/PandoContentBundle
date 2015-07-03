<?php
namespace BlackBoxCode\Pando\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class MethodArgumentDocument extends AbstractPhpcrDocument
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
     * @PHPCR\ReferenceOne(targetDocument="MethodDocument", strategy="hard")
     * @var MethodDocument
     **/
	private $method;

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

    /**
     * @return MethodDocument
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param MethodDocument $callback
     *
     * @return $this
     */
    public function setCallback(MethodDocument $callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
