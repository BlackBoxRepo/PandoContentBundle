<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Method;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\MethodArgument;

class MethodTest extends \PHPUnit_Framework_TestCase
{
    /** @var Method */
    private $method;

    public function setUp()
    {
        $this->method = new Method();
    }

    /**
     * @test
     */
    public function getArguments_isSorted()
    {
        $arg1 = new MethodArgument();
        $arg1
            ->setValue('c')
            ->setOrder(3)
        ;

        $arg2 = new MethodArgument();
        $arg2
            ->setValue('a')
            ->setOrder(1)
        ;

        $arg3 = new MethodArgument();
        $arg3
            ->setValue('b')
            ->setOrder(2)
        ;

        $this->method
            ->addArgument($arg1)
            ->addArgument($arg2)
            ->addArgument($arg3)
        ;

        $args = $this->method->getArguments();
        $this->assertEquals(1, $args->get(0)->getOrder());
        $this->assertEquals(2, $args->get(1)->getOrder());
        $this->assertEquals(3, $args->get(2)->getOrder());
    }
}
