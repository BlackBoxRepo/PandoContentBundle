<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Document;

/**
 * @Document(referenceable=true)
 */
class MethodArgument implements PrefixInterface, HierarchyInterface
{
    /**
     * @Id(strategy="UUID")
     * @var string
     **/
	private $id;

    /**
     * @Integer
     * @var integer
     **/
	private $argumentOrder;

    /**
     * @String
     * @var string
     **/
    private $value;

    /**
     * @ReferenceOne(targetDocument="Method", strategy="hard")
     * @var Method
     **/
	private $method;

    /**
     * @ReferenceOne(targetDocument="Method", strategy="hard")
     * @var Method
     **/
	private $callback;


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getArgumentOrder()
    {
        return $this->argumentOrder;
    }

    /**
     * @param int $argumentOrder
     * @return $this
     */
    public function setArgumentOrder($argumentOrder)
    {
        $this->argumentOrder = $argumentOrder;

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
