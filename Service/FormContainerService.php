<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use Symfony\Component\Form\FormInterface;

class FormContainerService
{
    /** @var FormInterface */
    private $form;


    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param FormInterface $form
     *
     * @return $this
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }
}
