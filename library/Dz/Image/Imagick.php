<?php
/**
 * DZ Framework
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 */

namespace Dz\Image;

/**
 * Imagick extension to simplify some common calls.
 *
 * @copyright Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author    LF Bittencourt <lf@dzestudio.com.br>
 */
class Imagick extends \Imagick
{
    /**
     * Blends the fill color with each pixel in the image.
     *
     * @param  string $color The fill color definition, e.g., "#ffcc00".
     * @param  float $alpha Opacity value. 1.0 is fully opaque and
     *                                     0.0 is fully transparent.
     * @return Imagick Provides fluent interface.
     */
    public function colorize($color, $alpha = 1)
    {
        $draw = new \ImagickDraw();

        $draw->setFillColor($color);

        if (is_float($alpha)) {
            $draw->setFillAlpha($alpha);
        }

        $geo = $this->getImageGeometry();
        $width = $geo['width'];
        $height = $geo['height'];

        $draw->rectangle(0, 0, $width, $height);

        $this->drawImage($draw);

        return $this;
    }

    /**
     * Extracts a region of the image from the center to the edges.
     *
     * @todo   Is this really needed? Could we use just Imagick::thumbnailImage?
     *
     * @param  integer $width The width of the crop.
     * @param  integer $height The height of the crop.
     * @return Imagick Provides fluent interface.
     */
    public function crop($width, $height)
    {
        $geo = $this->getImageGeometry();

        if ($geo['width'] / $width < $geo['height'] / $height) { // Vertical
            $this->cropImage(
                $geo['width'],
                floor($height * $geo['width'] / $width),
                0,
                ($geo['height'] - $height * $geo['width'] / $width) / 2
            );
        } else { // Horizontal
            $this->cropImage(
                ceil($width * $geo['height'] / $height),
                $geo['height'],
                ($geo['width'] - $width * $geo['height'] / $height) / 2,
                0
            );
        }

        $this->thumbnailImage($width, $height);

        return $this;
    }

    /**
     * Reads image from filename or URI.
     *
     * @uses   \Dz\Http\Client::getData()
     * @param  string $imageUri The filename or URI to load image from.
     * @return Imagick Provides fluent interface.
     */
    public function load($imageUri)
    {
        if (file_exists($imageUri)) {
            $this->readImage($imageUri);
        } else {
            $imageData = \Dz\Http\Client::getData($imageUri);

            $this->readImageBlob($imageData);
        }

        return $this;
    }

    /**
     * Pastes an image into a parent image.
     *
     * @param  \Imagick $image Object which holds the composite image.
     * @param  integer $x The column offset of the composited image.
     * @param  integer $y The row offset of the composited image.
     * @return Imagick Provides fluent interface.
     */
    public function paste($image, $x = 0, $y = 0)
    {
        $this->compositeImage($image, $image->getImageCompose(), $x, $y);

        return $this;
    }

    /**
     * Saves image to a file.
     *
     * @param  string $filename
     * @return boolean True if save operation has succeed, false otherwise.
     */
    public function save($filename)
    {
        $directory = dirname($filename);

        if (!file_exists($directory)) {
            mkdir($directory);
        }

        return $this->writeImage($filename);
    }

    /**
     * Outputs image to the HTTP response.
     *
     * @param string $contentType Content-type of the response image.
     */
    public function show($contentType = 'image/jpg')
    {
        header('Content-type: ' . $contentType);

        echo $this;

        die();
    }
}
