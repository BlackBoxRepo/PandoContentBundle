<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Service\FormContainerService;
use BlackBoxCode\Pando\ContentBundle\Service\FormService;
use BlackBoxCode\Pando\ContentBundle\Service\MethodService;
use Symfony\Component\HttpFoundation\RequestStack;


class FormServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var FormService */
    private $formService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|MethodService */
    private $mMethodService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|FormContainerService */
    private $mFormContainerService;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RequestStack */
    private $mRequestStack;


    public function setUp()
    {
        $this->mMethodService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\MethodService');
        $this->mFormContainerService = $this->getMock('BlackBoxCode\Pando\ContentBundle\Service\FormContainerService');
        $this->mRequestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');

        $this->formService = new FormService();
        $this->formService
            ->setMethodService($this->mMethodService)
            ->setFormContainerService($this->mFormContainerService)
            ->setRequestStack($this->mRequestStack)
        ;
    }

    /**
     * @test
     */
    public function processBlock_hasNoMethods()
    {
    }

    /**
     * @test
     */
    public function processBlock_hasMethods()
    {
    }
}
