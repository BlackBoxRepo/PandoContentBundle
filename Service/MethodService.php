<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Method;

class MethodService
{
    /** @var Container */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Executes the passed in method via the associated service that's retrieved from the container.
     * This gets called recursively to supply all the arguments for each given method.
     *
     * @return mixed
     */
    public function call(Method $method)
    {

    }
}