<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class MethodArgument extends BaseDocument
{
    /**
     * @PHPCR\Integer
     * @var integer
     **/
	private $order;

    /**
     * @PHPCR\String
     * @var string
     **/
    private $value;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Method", strategy="hard")
     * @var Method
     **/
	private $method;

    /**
     * @PHPCR\ReferenceOne(targetDocument="Method", strategy="hard")
     * @var Method
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
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

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

    /**
     * @return Method
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param Method $callback
     * @return $this
     */
    public function setCallback(Method $callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
