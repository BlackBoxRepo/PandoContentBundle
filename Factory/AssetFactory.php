<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Factory;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\File;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Image;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Javascript;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\Stylesheet;
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
                return new Stylesheet();
            case 'js':
                return new Javascript();
            default:
                if (strstr($this->getMimeType($file), 'image')) {
                    return new Image();
                }

                return new File();
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
