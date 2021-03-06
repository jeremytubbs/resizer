<?php

namespace Jeremytubbs\Resizer;

use Intervention\Image\ImageManager;
use League\Flysystem\FilesystemInterface;

class Resize
{
    use \Jeremytubbs\Resizer\ResizeHelpersTrait;

    protected $path;
    protected $imageManager;
    protected $imageFormat;
    protected $imageSizes;
    protected $image2x;
    protected $pathPrefix;

    /**
     * @param FilesystemInterface $path
     * @param ImageManager $imageManager
     */
    public function __construct(FilesystemInterface $path, ImageManager $imageManager, $imageFormat, $imageSizes, $image2x, $pathPrefix)
    {
        $this->setImageManager($imageManager);
        $this->setPath($path);
        $this->imageFormat = $imageFormat;
        $this->setImageSizes($imageSizes, $image2x);
        $this->pathPrefix = $pathPrefix;
    }

    public function makeImages($imagePath, $filePath = NULL, $rename = NULL, $config = NULL)
    {
        $results = null;
        $image = $this->imageManager->make($imagePath);
        $filename = $rename !== NULL ? $rename : pathinfo($imagePath)['filename'];
        foreach ($this->imageSizes as $type => $size) {
            $tempImage = clone $image;
            $height = $size[0];
            $width = $size[1];
            // prevent possible upsizing maintain aspect ratio
            $tempImage->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $tempImage->encode($this->imageFormat);
            $tempFilename = $filename .'_' . $type . '.' . $this->imageFormat;
            $tempPath = $filePath !== NULL ? $filePath . '/' . $tempFilename : $tempFilename;
            $saved_image = $this->path->put($tempPath, $tempImage);
            $results[$type] = $this->pathPrefix . '/' . $tempPath;
            unset($tempImage);
        }

        $data = [
            'output' => $results,
            'source' => $imagePath,
        ];

        // used with Laravel to fire event
        if ( defined('LARAVEL_START') ) \Event::fire('resizer', [$data]);

        return [
            'status' => 'ok',
            'data' => $data,
            'message' => 'Everything is okay!'
        ];
    }

    /**
     * @param ImageManager $imageManager
     */
    public function setImageManager(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @return mixed
     */
    public function getImageManager()
    {
        return $this->imageManager;
    }

    /**
     * @param FilesystemInterface $path
     */
    public function setPath(FilesystemInterface $path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}
