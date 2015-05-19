<?php
namespace BlackBoxCode\Pando\ContentBundle\Factory;

use BlackBoxCode\Pando\ContentBundle\Document\FileDocument;
use BlackBoxCode\Pando\ContentBundle\Document\ImageDocument;
use BlackBoxCode\Pando\ContentBundle\Document\JavascriptDocument;
use BlackBoxCode\Pando\ContentBundle\Document\StylesheetDocument;
use Symfony\Cmf\Bundle\MediaBundle\FileInterface;

class AssetFactory
{
    /**
     * Returns a new instance of the Document that matches the type of the file passed in
     *
     * @param \SplFileInfo $file
     * @return FileInterface
     */
    public function create(\SplFileInfo $file)
    {
        $extension = $file->getExtension();
        switch ($extension) {
            case 'css':
                return new StylesheetDocument();
            case 'js':
                return new JavascriptDocument();
            default:
                if (strstr($this->getMimeType($file), 'image')) {
                    return new ImageDocument();
                }

                return new FileDocument();
        }
    }

    /**
     * Returns the mime-type of the given file
     *
     * @param \SplFileInfo $file
     * @return string
     */
    public function getMimeType(\SplFileInfo $file)
    {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file->getPathname());
        finfo_close($fileInfo);

        return $mimeType;
    }
}
