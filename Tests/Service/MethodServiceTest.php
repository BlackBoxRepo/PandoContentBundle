<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Method;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\MethodArgument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Service;
use BlackBoxCode\Pando\Bundle\ContentBundle\Service\MethodService;
use Symfony\Component\DependencyInjection\Container;

class TestService
{
    public function foo() {}

    public function bar($a, $b, $c = 10) {
        return $a + $b + $c;
    }

    public function fooBar(Method $method) {}

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

    /** @var Service */
    private $service;

    /** @var \PHPUnit_Framework_MockObject_MockObject|TestService */
    private $mTestService;


    public function setUp()
    {
        $this->methodService = new MethodService();
        $this->mContainer = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $this->methodService->setContainer($this->mContainer);

        $this->service = new Service();
        $this->service
            ->setServiceName('test_service')
            ->setClassName('BlackBoxCode\Pando\Bundle\ContentBundle\Tests\Service\TestService')
        ;

        $this->mTestService = $this->getMock('TestService', ['foo', 'bar', 'fooBar']);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\MissingMethodArgumentException
     */
    public function call_noCallbackOrValue()
    {
        $argument = new MethodArgument();
        $argument
            ->setOrder(0)
        ;

        $method = new Method();
        $method
            ->setService($this->service)
            ->setName('fooBar')
            ->addArgument($argument)
        ;

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $this->methodService->call($method);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\UndefinedServiceException
     */
    public function call_serviceDoesNotExist()
    {
        $method = new Method();
        $method
            ->setService($this->service)
        ;

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(null)
        ;

        $this->methodService->call($method);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\BadMethodCallException
     */
    public function call_methodDoesNotExist()
    {
        $method = new Method();
        $method
            ->setService($this->service)
            ->setName('blah')
        ;

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn($this->mTestService)
        ;

        $this->methodService->call($method);
    }

    /**
     * @test
     */
    public function call_methodIsCalledAndReturned()
    {
        $method = new Method();
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

        $this->mTestService
            ->expects($this->once())
            ->method('foo')
            ->willReturn('abc')
        ;

        $return = $this->methodService->call($method);
        $this->assertEquals('abc', $return);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\WrongNumberOfArgumentsException
     */
    public function call_methodHasTooFewArguments()
    {
        $argument = new MethodArgument();
        $argument
            ->setOrder(0)
            ->setValue(2)
        ;

        $method = new Method();
        $method
            ->setService($this->service)
            ->setName('bar')
            ->addArgument($argument)
        ;

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn($this->mTestService)
        ;

        $this->methodService->call($method);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\WrongNumberOfArgumentsException
     */
    public function call_methodHasTooManyArguments()
    {
        $argument1 = new MethodArgument();
        $argument1
            ->setOrder(0)
            ->setValue(2)
        ;

        $argument2 = new MethodArgument();
        $argument2
            ->setOrder(1)
            ->setValue(2)
        ;

        $argument3 = new MethodArgument();
        $argument3
            ->setOrder(2)
            ->setValue(2)
        ;

        $argument4 = new MethodArgument();
        $argument4
            ->setOrder(3)
            ->setValue(2)
        ;

        $method = new Method();
        $method
            ->setService($this->service)
            ->setName('bar')
            ->addArgument($argument1)
            ->addArgument($argument2)
            ->addArgument($argument3)
            ->addArgument($argument4)
        ;

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn($this->mTestService)
        ;

        $this->methodService->call($method);
    }

    /**
     * @test
     */
    public function call_methodHasCorrectNumberOfRequiredArguments()
    {
        $argument1Value = 2;
        $argument1 = new MethodArgument();
        $argument1
            ->setOrder(0)
            ->setValue($argument1Value)
        ;

        $argument2Value = 15;
        $argument2 = new MethodArgument();
        $argument2
            ->setOrder(1)
            ->setValue($argument2Value);

        $method = new Method();
        $method
            ->setService($this->service)
            ->setName('bar')
            ->addArgument($argument1)
            ->addArgument($argument2)
        ;

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $return = $this->methodService->call($method);
        $this->assertEquals($argument1Value + $argument2Value + 10, $return);
    }

    /**
     * @test
     * @expectedException BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\BadArgumentTypeException
     */
    public function call_methodArgumentIsOfWrongType()
    {
        $argument = new MethodArgument();
        $argument
            ->setOrder(0)
            ->setValue(2)
        ;

        $method = new Method();
        $method
            ->setService($this->service)
            ->setName('fooBar')
            ->addArgument($argument)
        ;

        $this->service->addMethod($method);

        $this->mContainer
            ->expects($this->once())
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $this->methodService->call($method);
    }

    /**
     * @test
     */
    public function call_recursive()
    {
        $callback = new Method();
        $callback
            ->setService($this->service)
            ->setName('barFoo')
        ;

        $argument1 = new MethodArgument();
        $argument1
            ->setOrder(0)
            ->setCallback($callback)
        ;

        $argument2 = new MethodArgument();
        $argument2
            ->setOrder(1)
            ->setValue(20)
        ;

        $method = new Method();
        $method
            ->setService($this->service)
            ->setName('bar')
            ->addArgument($argument1)
            ->addArgument($argument2)
        ;

        $this->service
            ->addMethod($method)
            ->addMethod($callback)
        ;

        $this->mContainer
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->service->getServiceName())
            ->willReturn(new TestService())
        ;

        $return = $this->methodService->call($method);
        $this->assertEquals(130, $return);
    }
}
