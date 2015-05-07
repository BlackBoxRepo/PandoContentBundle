<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\BadArgumentTypeException;
use BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\BadMethodCallException;
use BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\MissingMethodArgumentException;
use BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\UndefinedServiceException;
use BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\WrongNumberOfArgumentsException;
use Symfony\Component\DependencyInjection\Container;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Method;

class MethodService
{
    /** @var Container */
    private $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Executes the passed in method via the associated service that's retrieved from the container.
     * This gets called recursively to supply all the arguments for each given method.
     *
     * @param Method $method
     *
     * @throws WrongNumberOfArgumentsException if we have more or less arguments than the method requires
     * @throws BadArgumentTypeException if the type of a given argument does not match the method arguments expected type
     * @throws MissingMethodArgumentException if argument has neither callback nor value
     * @throws UndefinedServiceException if service doesn't exist
     * @throws BadMethodCallException if the service doesn't have the given method
     *
     * @return mixed
     */
    public function call(Method $method)
    {

    }
}