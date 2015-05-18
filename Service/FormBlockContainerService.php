<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

class FormBlockContainerService
{
    /** @var FormInterface */
    private $form;

    /** @var BlockInterface */
    private $block;


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
     * @return BlockInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param BlockInterface $block
     *
     * @return $this
     */
    public function setBlock(BlockInterface $block)
    {
        $this->block = $block;

        return $this;
    }
}
