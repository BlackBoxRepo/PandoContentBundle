<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\MethodDocument;
use BlackBoxCode\Pando\ContentBundle\Document\MethodArgumentDocument;

class MethodDocumentTest extends \PHPUnit_Framework_TestCase
{
    /** @var MethodDocument */
    private $method;

    public function setUp()
    {
        $this->method = new MethodDocument();
    }

    /**
     * @test
     */
    public function getArguments_isSorted()
    {
        $arg1 = new MethodArgumentDocument();
        $arg1
            ->setValue('c')
            ->setOrder(3)
        ;

        $arg2 = new MethodArgumentDocument();
        $arg2
            ->setValue('a')
            ->setOrder(1)
        ;

        $arg3 = new MethodArgumentDocument();
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
