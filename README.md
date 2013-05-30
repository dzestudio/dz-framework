DZ Framework
============

DZ Framework is an ultra little classes package provided by [DZ Estúdio](http://www.dzestudio.com.br).

Components
----------

### Dz\Http\Client

Provides common methods for receiving data from a URI.

``` php
<?php

$uri = 'http://www.dzestudio.com.br';

// Downloads the contents of the $uri.
$contents = \Dz\Http\Client::getData($uri);
```

### Dz\Image\Imagick

Imagick extension to simplify some common calls. As an example, take these images:

![Credits to Jose Maria Cuellar](http://farm1.staticflickr.com/16/20983487_1d88ca94e7.jpg)

![A transparent PNG :-)](http://static.dzestudio.com.br/github/white-logo.png)

If you execute code below...

``` php
<?php

$file = 'http://farm1.staticflickr.com/16/20983487_1d88ca94e7.jpg';
$image = new \Dz\Image\Imagick($file);

// Blends the fill color with each pixel in the image.
$image->colorize('#54a80f', 0.5);

// Loads the watermark.
$watermarkFile = 'http://static.dzestudio.com.br/github/white-logo.png';
$watermark = new \Dz\Image\Imagick($watermarkFile);

// Pastes the watermark into the image.
$image->paste($watermark, 120, 250);

// Extracts a 300x300 px region of the image from the center to the edges.
$image->crop(300, 300);

// Outputs image to the HTTP response.
$image->show();
```
... the result will be:

![](http://static.dzestudio.com.br/github/result.png)

### Dz\Security\Hash

Hash generator class.

``` php
<?php

use \Dz\Security\Hash;

// Let's say that user has filled these two variables.
$email = 'example@example.com';
$password = 'mYs3cR3tP4S5W0Rd!';

// Think about some reproducible salt schema...
$saltBase = md5('Kynodontas#' . $email);

// Now, let's hash!
$hash = new Hash(array('saltBase' => $saltBase));

// Save hash somewhere.
$passwordHash = $hash->crypt($password);

// Now, let's check. One more time, pretend that there's an user here!
$emailInput = 'example@example.com';
$passwordInput = 'wR0NgP4S5W0Rd!';

// Here is our reproducible salt schema.
$saltBase = md5('Kynodontas#' . $emailInput);
$hash = new Hash(array('saltBase' => $saltBase));

if ($hash->check($passwordHash, $passwordInput)) {
    // Hashes match :-)
} else {
    // Something wrong...
}
```

### Dz\Geocode

This class uses Google Maps API to convert addresses (like "1600 Amphitheatre Parkway, Mountain View, CA") into geographic coordinates (like latitude 37.423021 and longitude -122.083739), which you can use to place markers or position a map.

An example:

``` php
<?php

$address = 'Rua Vinte e Quatro de Outubro, 353';
$latLng = \Dz\Geocode::getLatLng($address);

echo 'Latitude: ', $latLng->lat, PHP_EOL;
echo 'Longitude: ', $latLng->lng, PHP_EOL;
```