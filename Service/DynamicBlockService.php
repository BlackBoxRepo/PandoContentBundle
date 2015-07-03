<?php
namespace BlackBoxCode\Pando\ContentBundle\Service;

use BlackBoxCode\Pando\ContentBundle\Document\BlockDocument;
use BlackBoxCode\Pando\ContentBundle\Exception\Service\MissingBlockTemplateException;
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

    /** @var FormContainerService */
    private $formContainerService;

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
     * @param FormContainerService $formContainerService
     *
     * @return $this
     */
    public function setFormContainerService(FormContainerService $formContainerService)
    {
        $this->formContainerService = $formContainerService;

        return $this;
    }

    /**
     * Process form methods for the form/block combo
     *
     * @param BlockContextInterface $blockContext
     * @param Response $response
     *
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        /** @var BlockDocument $block */
        $block = $blockContext->getBlock();
        $this->formService->processBlock($block);
        $template = $block->getTemplate();
        if (is_null($template)) {
            throw new MissingBlockTemplateException("Template must be defined on for block " . $block->getName());
        }
        return $this->getTemplating()->renderResponse($template->getName(), $block->getViewVariables()->toArray());
    }

    /**
     * Sets block on FormBlockContainerService.  Runs populateBlockVariables on the block.
     *
     * @param BlockInterface $block
     */
    public function load(BlockInterface $block)
    {
        $this->blockVariableService
            ->setBlock($block)
            ->populateBlockVariables()
        ;
    }
}
