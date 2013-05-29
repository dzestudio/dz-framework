DZ Framework
============

DZ Framework is a ultra little classes package provided by [DZ Estúdio](http://www.dzestudio.com.br).

Components
----------

### Dz\_Http\_Client

Provides common methods for receiving data from a URI.

``` php
<?php

$uri = 'http://www.dzestudio.com.br';

// Downloads the contents of the $uri.
$contents = \Dz\_Http\_Client::getData($uri);
```

### Dz\_Image\_Imagick

Imagick extension to simplify some common calls.

``` php
<?php

$imagick = new \Dz\_Image\_Imagick('example.png');

// Blends the fill color with each pixel in the image.
$imagick->colorize('#ffcc00', 0.35);

// Outputs image to the HTTP response.
$imagick->show();
```

### Dz\_Security\_Hash

TBD.

### Dz_Geocode

This class uses Google Maps API to convert addresses (like "1600 Amphitheatre Parkway, Mountain View, CA") into geographic coordinates (like latitude 37.423021 and longitude -122.083739), which you can use to place markers or position a map.

An example:

``` php
<?php

$address = 'Rua Vinte e Quatro de Outubro, 353';
$latLng = \Dz_Geocode::getLatLng($address);

echo 'Latitude: ', $latLng->lat, PHP_EOL;
echo 'Longitude: ', $latLng->lng, PHP_EOL;
```