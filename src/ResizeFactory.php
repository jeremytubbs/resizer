<?php

namespace Jeremytubbs\Resizer;

use InvalidArgumentException;
use Intervention\Image\ImageManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class ResizeFactory
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return Resizer
     */
    public function getResizer()
    {
        $resizer = new Resize(
            $this->getFilesystem(),
            $this->getImageManager(),
            $this->getImageFormat(),
            $this->getImageSizes(),
            $this->getImage2x(),
            $this->getPathPrefix()
        );

        return $resizer;
    }

    /**
     * @return Filesystem|void
     */
    public function getFilesystem()
    {
        if (!isset($this->config['path'])) {
            throw new InvalidArgumentException('A "source" file system must be set.');
        }

        if (is_string($this->config['path'])) {
            return new Filesystem(
                new Local($this->config['path'])
            );
        }

        return $this->config['source'];
    }

    /**
     * @return ImageManager
     */
    public function getImageManager()
    {
        $driver = 'gd';

        if (isset($this->config['driver'])) {
            $driver = $this->config['driver'];
        }

        return new ImageManager([
            'driver' => $driver,
        ]);
    }

    public function getImageFormat()
    {
        $imageFormat = 'jpg';

        if (isset($this->config['format'])) {
            $imageFormat = $this->config['format'];
        }

        return $imageFormat;
    }

    public function getImageSizes()
    {
        if (!isset($this->config['sizes'])) {
            return;
        }

        if (isset($this->config['sizes'])) {
            $imageSizes = $this->config['sizes'];
        }

        return $imageSizes;
    }

    public function getImage2x()
    {
        $image2x = true;

        if (isset($this->config['image2x'])) {
            $image2x = $this->config['image2x'];
        }

        return $image2x;
    }

    public function getPathPrefix()
    {
        if (isset($this->config['path'])) {
            $pathPrefix = $this->config['path'];
        }

        return $pathPrefix;
    }

    /**
     * @param array $config
     * @return Resizer
     */
    public static function create(array $config = [])
    {
        return (new self($config))->getResizer();
    }
}
