<?php

namespace Jeremytubbs\Resizer;

use Intervention\Image\ImageManager;
use League\Flysystem\FilesystemInterface;

class Resize
{
    protected $path;
    protected $imageManager;
    protected $imageFormat;
    protected $imageSizes;
    protected $image2x;

    /**
     * @param FilesystemInterface $path
     * @param ImageManager $imageManager
     */
    public function __construct(FilesystemInterface $path, ImageManager $imageManager, $imageFormat, $imageSizes, $image2x)
    {
        $this->setImageManager($imageManager);
        $this->setPath($path);
        $this->imageFormat = $imageFormat;
        $this->setImageSizes($imageSizes, $image2x);
    }

    public function makeImages($imagePath, $file = NULL)
    {
        $results = null;
        $image = $this->imageManager->make($imagePath);
        $filename = $file !== NULL ? $file : pathinfo($imagePath)['filename'];
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
            $tempPath =  $filename .'_' . $type . '.' . $this->imageFormat;
            $this->path->put($tempPath, $tempImage);
            $results[$type] = $tempPath;
            unset($tempImage);
        }
        return [
            'status' => 'ok',
            'data' => $results,
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

    public function setImageSizes($imageSizes, $image2x)
    {
        if ($image2x) {
            foreach ($imageSizes as $type => $size) {
                $height = $size[0] ? $size[0] * 2 : $size[0];
                $width = $size[1] ? $size[1] * 2 : $size[1];
                $imageSizes[$type . '_2x'] = [$height, $width];
            }
        }
        return $this->imageSizes = $imageSizes;
    }

}
