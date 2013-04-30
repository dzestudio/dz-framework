<?php

/**
 * DZ Framework
 *
 * @category   Dz
 * @package    Dz_Image
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 * @version    $Id$
 */

/**
 * @TODO Document.
 *
 * @category   Dz
 * @package    Dz_Image
 * @copyright  Copyright (c) 2012 DZ Estúdio (http://www.dzestudio.com.br)
 */
class Dz_Image_Imagick extends Imagick
{
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

    public function crop($width, $height)
    {
        $geometry = $this->getImageGeometry();

        if ($geometry['width'] / $width < $geometry['height'] / $height) { // Vertical
            $this->cropImage($geometry['width'], floor($height * $geometry['width'] / $width), 0, ($geometry['height'] - $height * $geometry['width'] / $width) / 2);
        } else { // Horizontal
            $this->cropImage(ceil($width * $geometry['height'] / $height), $geometry['height'], ($geometry['width'] - $width * $geometry['height'] / $height) / 2, 0);
        }

        $this->ThumbnailImage($width, $height);
    }

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

    public function paste($image, $x = 0, $y = 0)
    {
        $this->compositeImage($image, $image->getImageCompose(), $x, $y);
    }

    /**
     *
     * @param string $filename
     * @return bool
     */
    public function save($filename)
    {
        $directory = dirname($filename);

        if (!file_exists($directory)) {
            mkdir($directory);
        }

        return $this->writeImage($filename);
    }

    public function show($contentType = 'image/png')
    {
        header('Content-type: ' . $contentType);

        echo $this;

        die();
    }
}