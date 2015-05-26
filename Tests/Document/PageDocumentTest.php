<?php
namespace BlackBoxCode\Pando\ContentBundle\Tests\Service;

use BlackBoxCode\Pando\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\ContentBundle\Document\FormPageDocument;
use BlackBoxCode\Pando\ContentBundle\Document\PageDocument;
use Doctrine\Common\Collections\ArrayCollection;

class PageDocumentTest extends \PHPUnit_Framework_TestCase
{
    /** @var PageDocument */
    private $page;


    public function setUp()
    {
        $this->page = new PageDocument();
    }

    /**
     * @test
     */
    public function getFormNames()
    {
        $formName1 = 'form_1';
        $formName2 = 'form_2';

        $form1 = new FormDocument();
        $form1->setName('form_1');

        $form2 = new FormDocument();
        $form2->setName('form_2');

        $formPage1 = new FormPageDocument();
        $formPage1->setForm($form1);

        $formPage2 = new FormPageDocument();
        $formPage2->setForm($form2);

        $this->page
            ->addFormPage($formPage1)
            ->addFormPage($formPage2)
        ;

        $return = $this->page->getFormNames();
        $this->assertEquals(new ArrayCollection([$formName1, $formName2]), $return);
    }

    /**
     * @test
     */
    public function getFormPageByFormName()
    {
        $formName1 = 'form_1';
        $formName2 = 'form_2';

        $form1 = new FormDocument();
        $form1->setName($formName1);

        $form2 = new FormDocument();
        $form2->setName($formName2);

        $formPage1 = new FormPageDocument();
        $formPage1->setForm($form1);

        $formPage2 = new FormPageDocument();
        $formPage2->setForm($form2);

        $this->page
            ->addFormPage($formPage1)
            ->addFormPage($formPage2)
        ;

        $return = $this->page->getFormPageByFormName($formName2);
        $this->assertEquals($formPage2, $return);
    }
}
