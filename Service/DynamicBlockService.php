<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class DynamicBlockService extends BaseBlockService
{
    /** @var EngineInterface */
    protected $templating;

    /** @var string */
    protected $name;

    /** @var FormService */
    private $formService;

    /** @var BlockVariableService */
    private $blockVariableService;

    /** @var FormBlockContainerService */
    private $formBlockContainerService;

    /**
     * This shouldn't do anything
     */
    public function __construct()
    {

    }

    /**
     * @param EngineInterface $templating
     *
     * @return $this
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param FormService $formService
     *
     * @return $this
     */
    public function setFormService(FormService $formService)
    {
        $this->formService = $formService;

        return $this;
    }

    /**
     * @param BlockVariableService $blockVariableService
     *
     * @return $this
     */
    public function setBlockVariableService(BlockVariableService $blockVariableService)
    {
        $this->blockVariableService = $blockVariableService;

        return $this;
    }

    /**
     * @param FormBlockContainerService $formBlockContainerService
     *
     * @return $this
     */
    public function setFormBlockContainerService(FormBlockContainerService $formBlockContainerService)
    {
        $this->formBlockContainerService = $formBlockContainerService;

        return $this;
    }

    /**
     * Process form methods for the form/block combo
     *
     * @param BlockContextInterface $blockContext
     * @param Response              $response
     *
     * @return ...
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {

    }

    /**
     * Sets block on FormBlockContainerService.  Runs populateBlockVariables on the block.
     *
     * @param BlockInterface $block
     */
    public function load(BlockInterface $block)
    {

    }
}
