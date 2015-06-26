<?php

namespace BlackBoxCode\Pando\ContentBundle\Twig\Loader;


use BlackBoxCode\Pando\ContentBundle\Document\TemplateDocument;
use Doctrine\ODM\PHPCR\DocumentManager;
use Twig_Error_Loader;

class PhpcrLoader implements \Twig_LoaderInterface, \Twig_ExistsLoaderInterface {
    const PATH_PREFIX = '/cms/asset/templates';

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return bool If the template source code is handled by this loader or not
     */
    public function exists($name)
    {
        return !is_null($this->getDocument($name));
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The template source code
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getSource($name)
    {
        /** @var TemplateDocument $doc */
        $doc = $this->getDocument($name);
        if (is_null($doc) || !($doc instanceof TemplateDocument))
        {
            throw new Twig_Error_Loader(sprintf('Template "%s" does not exist.', $name));
        }
        $content = $doc->getContent();
        return $content;
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getCacheKey($name)
    {
        return $name;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string $name The template name
     * @param timestamp $time The last modification time of the cached template
     *
     * @return bool true if the template is fresh, false otherwise
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    public function isFresh($name, $time)
    {
        /** @var TemplateDocument $doc */
        $doc = $this->getDocument($name);
        if(is_null($doc) || is_null($doc->getLastModified()))
        {
            return false;
        }
        return $doc->getLastModified()->getTimestamp() <= $time;
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     * @param DocumentManager $documentManager
     */
    public function setDocumentManager(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    //'AcmeDemoBundle:Bla:layout.html.twig'
    private function getPath($templateName)
    {
        $parts = explode(':', $templateName);
        if(empty($parts[1]))
        {
            unset($parts[1]);
        }
        $ret = self::PATH_PREFIX . '/' . implode('/', $parts);
        return $ret;
    }

    private function getDocument($name)
    {
        return $this->getDocumentManager()->find(null, $this->getPath($name));
    }
}