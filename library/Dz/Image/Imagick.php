<?php
/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Image
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id$
 */

/**
 * Imagick extension to simplify some common calls.
 *
 * @category   Dz
 * @package    Dz_Image
 * @copyright  Copyright (c) 2012-2013 DZ Estúdio (http://www.dzestudio.com.br)
 * @author     LF Bittencourt <lf@dzestudio.com.br>
 */
class Dz_Image_Imagick extends \Imagick
{
    /**
     * Blends the fill color with each pixel in the image.
     *
     * @param string $color The fill color definition, e.g., "#ffcc00".
     * @param float $alpha Opacity value. 1.0 is fully opaque and
     *                                    0.0 is fully transparent.
     */
    public function colorize($color, $alpha = 1)
    {
        $draw = new ImagickDraw();

        $draw->setFillColor($color);

        if (is_float($alpha)) {
            $draw->setFillAlpha($alpha);
        }

        $geometry = $this->getImageGeometry();
        $width = $geometry['width'];
        $height = $geometry['height'];

        $draw->rectangle(0, 0, $width, $height);

        $this->drawImage($draw);
    }

    /**
     * Extracts a region of the image from the center to the edges.
     *
     * @param integer $width The width of the crop.
     * @param integer $height The height of the crop.
     * @todo  Is this really needed? Could we use just Imagick::thumbnailImage?
     */
    public function crop($width, $height)
    {
        $geometry = $this->getImageGeometry();

        // Vertical
        if ($geometry['width'] / $width < $geometry['height'] / $height) {
            $this->cropImage(
                $geometry['width'],
                floor($height * $geometry['width'] / $width),
                0,
                ($geometry['height'] - $height * $geometry['width'] / $width) / 2
            );

        // Horizontal
        } else {
            $this->cropImage(
                ceil($width * $geometry['height'] / $height),
                $geometry['height'],
                ($geometry['width'] - $width * $geometry['height'] / $height) / 2,
                0
            );
        }

        $this->thumbnailImage($width, $height);
    }

    /**
     * Reads image from filename or URI.
     *
     * @uses  \Dz_Http_Client::getData()
     * @param string $imageUri The filename or URI to load image from.
     */
    public function load($imageUri)
    {
        if (file_exists($imageUri)) {
            $this->readImage($imageUri);
        } else {
            /**
             * @see \Dz_Http_Client
             */
            require_once 'Dz/Http/Client.php';

            $imageData = \Dz_Http_Client::getData($imageUri);

            $this->readImageBlob($imageData);
        }
    }

    /**
     * Pastes an image into a parent image.
     *
     * @param \Imagick $image Object which holds the composite image.
     * @param integer $x The column offset of the composited image.
     * @param integer $y The row offset of the composited image.
     */
    public function paste($image, $x = 0, $y = 0)
    {
        $this->compositeImage($image, $image->getImageCompose(), $x, $y);
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
    public function show($contentType = 'image/png')
    {
        header('Content-type: ' . $contentType);

        echo $this;

        die();
    }
}