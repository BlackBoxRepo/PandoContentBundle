<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\ArgumentDocument;
use BlackBoxCode\Pando\ContentBundle\Document\MethodDocument;
use BlackBoxCode\Pando\ContentBundle\Document\MethodArgumentDocument;
use BlackBoxCode\Pando\ContentBundle\Document\ServiceDocument;
use BlackBoxCode\Pando\ContentBundle\Service\MethodService;
use Symfony\Component\DependencyInjection\Container;

class TestService
{
    public function foo() {}

    public function bar($a, $b, $c = 10) {
        return $a + $b + $c;
    }

    public function fooBar(MethodDocument $method) {}

    public function barFoo()
    {
        return 100;
    }
}

class MethodServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var MethodService */
    private $methodService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Container */
    private $mContainer;

    /** @var ServiceDocument */
    private $service;

    /** @var \PHPUnit_Framework_MockObject_MockObject|TestService */
    private $mTestService;


    public function setUp()
    {
        $this->methodService = new MethodService();
        $this->mContainer = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $this->methodService->setContainer($this->mContainer);

        $this->service = new ServiceDocument();
        $this->service
            ->setServiceName('test_service')
            ->setClassName('BlackBoxCode\Pando\ContentBundle\Tests\Service\TestService')
        ;

        $this->mTestService = $this->getMock('TestService', ['foo', 'bar', 'fooBar']);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\MissingMethodArgumentException
     */
    public function call_noCallbackOrValue()
    {
        $argument = new ArgumentDocument();
        $argument
            ->setOrder(0)
        ;

        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('fooBar')
        ;

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);
        $methodArgument->addArgument($argument);

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $this->methodService->call($methodArgument);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\UndefinedServiceException
     */
    public function call_serviceDoesNotExist()
    {
        $method = new MethodDocument();
        $method
            ->setService($this->service)
        ;

        $this->service->addMethod($method);

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(null)
        ;

        $this->methodService->call($methodArgument);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\BadMethodCallException
     */
    public function call_methodDoesNotExist()
    {
        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('blah')
        ;

        $this->service->addMethod($method);

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn($this->mTestService)
        ;

        $this->methodService->call($methodArgument);
    }

    /**
     * @test
     */
    public function call_methodIsCalledAndReturned()
    {
        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('foo')
        ;

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn($this->mTestService)
        ;

        $this->service->addMethod($method);

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);

        $this->mTestService
            ->expects($this->once())
            ->method('foo')
            ->willReturn('abc')
        ;

        $return = $this->methodService->call($methodArgument);
        $this->assertEquals('abc', $return);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\WrongNumberOfArgumentsException
     */
    public function call_methodHasTooFewArguments()
    {
        $argument = new ArgumentDocument();
        $argument
            ->setOrder(0)
            ->setValue(2)
        ;

        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('bar')
        ;

        $this->service->addMethod($method);

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);
        $methodArgument->addArgument($argument);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn($this->mTestService)
        ;

        $this->methodService->call($methodArgument);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\WrongNumberOfArgumentsException
     */
    public function call_methodHasTooManyArguments()
    {
        $argument1 = new ArgumentDocument();
        $argument1
            ->setOrder(0)
            ->setValue(2)
        ;

        $argument2 = new ArgumentDocument();
        $argument2
            ->setOrder(1)
            ->setValue(2)
        ;

        $argument3 = new ArgumentDocument();
        $argument3
            ->setOrder(2)
            ->setValue(2)
        ;

        $argument4 = new ArgumentDocument();
        $argument4
            ->setOrder(3)
            ->setValue(2)
        ;

        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('bar')
        ;

        $this->service->addMethod($method);

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);
        $methodArgument->addArgument($argument1);
        $methodArgument->addArgument($argument2);
        $methodArgument->addArgument($argument3);
        $methodArgument->addArgument($argument4);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn($this->mTestService)
        ;

        $this->methodService->call($methodArgument);
    }

    /**
     * @test
     */
    public function call_methodHasCorrectNumberOfRequiredArguments()
    {
        $argument1Value = 2;
        $argument1 = new ArgumentDocument();
        $argument1
            ->setOrder(0)
            ->setValue($argument1Value)
        ;

        $argument2Value = 15;
        $argument2 = new ArgumentDocument();
        $argument2
            ->setOrder(1)
            ->setValue($argument2Value);

        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('bar')
        ;

        $this->service->addMethod($method);

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);
        $methodArgument->addArgument($argument1);
        $methodArgument->addArgument($argument2);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $return = $this->methodService->call($methodArgument);
        $this->assertEquals($argument1Value + $argument2Value + 10, $return);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\ContentBundle\Exception\Service\BadArgumentTypeException
     */
    public function call_methodArgumentIsOfWrongType()
    {
        $argument = new ArgumentDocument();
        $argument
            ->setOrder(0)
            ->setValue(2)
        ;

        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('fooBar')
        ;

        $this->service->addMethod($method);

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);
        $methodArgument->addArgument($argument);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $this->methodService->call($methodArgument);
    }

    /**
     * @test
     */
    public function call_recursive()
    {
        $callback = new MethodDocument();
        $callback
            ->setService($this->service)
            ->setName('barFoo')
        ;

        $methodArgumentCallback = new MethodArgumentDocument();
        $methodArgumentCallback->setMethod($callback);

        $argument1 = new ArgumentDocument();
        $argument1
            ->setOrder(0)
            ->setCallback($methodArgumentCallback)
        ;


        $argument2 = new ArgumentDocument();
        $argument2
            ->setOrder(1)
            ->setValue(20)
        ;

        $method = new MethodDocument();
        $method
            ->setService($this->service)
            ->setName('bar')
        ;

        $this->service
            ->addMethod($method)
            ->addMethod($callback)
        ;

        $methodArgument = new MethodArgumentDocument();
        $methodArgument->setMethod($method);
        $methodArgument->addArgument($argument1);
        $methodArgument->addArgument($argument2);

        $this->mContainer
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $return = $this->methodService->call($methodArgument);
        $this->assertEquals(130, $return);
    }
}
