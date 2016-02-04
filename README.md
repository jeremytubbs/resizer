Resizer
==

Resize and create high-resolution images for use with picturefill scrset. Does not support cropping.

Example implementation -
```php
    // Set sizes array
    $sizes = [
        'thumb'   => [165, null],
        'preview' => [360, 420]
    ];

    // Setup resizer - path and sizes are required
    $resizer = Jeremytubbs\Resizer\ResizeFactory::create([
        'path'    => 'images',  // Export path for images
        'driver'  => 'imagick', // Choose between gd and imagick support the default is gd
        'format'  => 'png',     // Default is jpg
        'sizes'   => $sizes,    // Array of image sizes
        'image2x' => false      // Default is true
    ]);

    $resizer->makeImages(
        'images/KISS.jpg',        // Path to source image
        'keep-it-simple-stupid'); // Rename image
```

Example response -
```javascript
{
  status: "ok",
    data: {
      thumb: "keep-it-simple-stupid_thumb.png",
      preview: "keep-it-simple-stupid_preview.png",
      thumb_2x: "keep-it-simple-stupid_thumb_2x.png",
      preview_2x: "keep-it-simple-stupid_preview_2x.png"
    },
  message: "Everything is okay!"
}
```

### Supported Image Libraries
- GD Library (>=2.0)
- Imagick PHP extension (>=6.5.7)