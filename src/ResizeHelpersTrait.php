<?php

namespace Jeremytubbs\Resizer;

trait ResizeHelpersTrait {

    public function setImageSizes($imageSizes, $image2x)
    {
        if ($image2x) {
            foreach ($imageSizes as $type => $size) {
                $height = $size[0] ? $size[0] * 2 : $size[0];
                $width = $size[1] ? $size[1] * 2 : $size[1];
                $imageSizes[$type . '@2x'] = [$height, $width];
            }
        }
        return $this->imageSizes = $imageSizes;
    }
}