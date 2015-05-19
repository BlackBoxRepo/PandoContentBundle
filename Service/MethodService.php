<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\MethodArgumentDocument;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\BadArgumentTypeException;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\BadMethodCallException;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\MissingMethodArgumentException;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\UndefinedServiceException;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\WrongNumberOfArgumentsException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;
use BlackBoxCode\Pando\ContentBundle\Document\MethodDocument;

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
     * @param MethodDocument $method
     *
     * @throws WrongNumberOfArgumentsException if we have more or less arguments than the method requires
     * @throws BadArgumentTypeException if the type of a given argument does not match the method arguments expected type
     * @throws MissingMethodArgumentException if argument has neither callback nor value
     * @throws UndefinedServiceException if service doesn't exist
     * @throws BadMethodCallException if the service doesn't have the given method
     *
     * @return mixed
     */
    public function call(MethodDocument $method)
    {
        $serviceName = $method->getService()->getServiceName();
        $service = $this->container->get($serviceName);
        if (null === $service) {
            throw new UndefinedServiceException(sprintf('"%s" is not a service', $serviceName));
        }

        $methodName = $method->getName();
        try {
            $r = new \ReflectionMethod($service, $methodName);
        } catch (\ReflectionException $e) {
            throw new BadMethodCallException(sprintf('"%s" is not a method in %s', $methodName, $serviceName));
        }

        $methodArguments = $method->getArguments();
        $numberOfArgs = $methodArguments->count();

        $minArgs = $r->getNumberOfRequiredParameters();
        $maxArgs = $r->getNumberOfParameters();
        if ($numberOfArgs < $minArgs || $numberOfArgs > $maxArgs) {
            $range = $minArgs === $maxArgs ? $minArgs : "$minArgs-$maxArgs";
            throw new WrongNumberOfArgumentsException(
                sprintf('Method "%s" in %s expects %s arguments, got %d', $methodName, $serviceName, $range, $numberOfArgs)
            );
        }

        return $service->$methodName(...$this->buildArgumentArray($r->getParameters(), $methodArguments));
    }

    /**
     * @param array $params
     * @param ArrayCollection $methodArguments
     *
     * @return array
     */
    private function buildArgumentArray(array $params, ArrayCollection $methodArguments)
    {
        $arguments = [];

        for ($i = 0; $i < count($params); $i++) {
            if (!isset($methodArguments[$i])) {
                break;
            }

            /** @var /ReflectionParameter $parameter */
            $param = $params[$i];

            /** @var MethodArgumentDocument $argument */
            $methodArgument = $methodArguments[$i];

            $callback = $methodArgument->getCallback();
            $value = $methodArgument->getValue();

            $argumentValue = null;
            if (null !== $callback) {
                $argumentValue = $this->call($callback);
            } else if (null !== $value) {
                $argumentValue = $methodArgument->getValue();
            } else {
                throw new MissingMethodArgumentException(
                    sprintf(
                        'The argument at position %d has no value or callback defined',
                        $methodArgument->getOrder()
                    )
                );
            }

            $paramType = $param->getClass();
            if (null !== $paramType || is_object($argumentValue)) {
                if (null !== $paramType) {
                    $paramType = $paramType->getName();
                }

                $argumentType = null;
                if (is_object($argumentValue)) {
                    $argumentType = get_class($argumentValue);
                }

                if ($argumentType !== $paramType) {
                    throw new BadArgumentTypeException(
                        sprintf(
                            'The argument at position %d was expected to be of type "%s"',
                            $methodArgument->getOrder(),
                            $paramType
                        )
                    );
                }
            }

            $arguments[] = $argumentValue;
        }

        return $arguments;
    }
}
