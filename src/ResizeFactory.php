<?php

namespace Jeremytubbs\Resizer;

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
     * @return Deepzoom
     */
    public function getResizer()
    {
        $resizer = new Resize(
            $this->getExportPath(),
            $this->getImageManager(),
            $this->getImageFormat(),
            $this->getImageSizes(),
            $this->getImage2x()
        );

        return $resizer;
    }

    /**
     * @return Filesystem|void
     */
    public function getExportPath()
    {
        if (!isset($this->config['path'])) {
            return;
        }

        if (is_string($this->config['path'])) {
            return new Filesystem(
                new Local($this->config['path'])
            );
        }
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

    /**
     * @param array $config
     * @return Deepzoom
     */
    public static function create(array $config = [])
    {
        return (new self($config))->getResizer();
    }
}
