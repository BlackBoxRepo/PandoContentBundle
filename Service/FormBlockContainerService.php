<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

class FormBlockContainerService
{
    /** @var FormInterface */
    private $form;

    /** @var BlockInterface */
    private $blockInterface;


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

    /**
     * @return mixed
     */
    public function getBlockInterface()
    {
        return $this->blockInterface;
    }

    /**
     * @param BlockInterface $blockInterface
     *
     * @return $this
     */
    public function setBlockInterface(BlockInterface $blockInterface)
    {
        $this->blockInterface = $blockInterface;

        return $this;
    }
}
