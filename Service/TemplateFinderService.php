<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\PageDocument;
use Symfony\Cmf\Bundle\RoutingBundle\Routing\DynamicRouter;
use Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class TemplateFinderService
{
    /** @var DynamicRouter */
    private $dynamicRouter;

    /** @var RequestStack */
    private $requestStack;


    /**
     * @param DynamicRouter $dynamicRouter
     *
     * @return $this;
     */
    public function setDynamicRouter(DynamicRouter $dynamicRouter)
    {
        $this->dynamicRouter = $dynamicRouter;

        return $this;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return $this;
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        return $this;
    }

    /**
     * Returns the default template for a given page
     *
     * @param PageDocument $page
     *
     * @return string|null
     */
    public function find(PageDocument $page)
    {
        $request = $this->requestStack->getCurrentRequest();

        $template = $page->getDefault('_template');
        if ($template === null) {
            $defaults = [];

            /** @var RouteEnhancerInterface $enhancer */
            foreach ($this->dynamicRouter->getRouteEnhancers() as $enhancer) {
                $defaults = array_merge($defaults, $enhancer->enhance(['_content' => $page], $request));
            }

            if (array_key_exists('_template', $defaults)) {
                $template = $defaults['_template'];
            }
        }

        return $template;
    }
}
