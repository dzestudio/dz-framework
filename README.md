DZ Framework
============

[![Build Status](https://travis-ci.org/dzestudio/dz-framework.png?branch=master)](https://travis-ci.org/dzestudio/dz-framework)

DZ Framework is an ultra little PHP classes package provided by [DZ Estúdio](http://www.dzestudio.com.br). Its features are a hash generator and a geographic conversions class.

Components
----------

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

### Dz\Maps\Converter

This class uses Google Maps API to convert addresses (like "1600 Amphitheatre Parkway, Mountain View, CA") into geographic coordinates (like latitude 37.423021 and longitude -122.083739), which you can use to place markers or position a map.

An example:

``` php
<?php

$address = 'Rua Vinte e Quatro de Outubro, 353';
$latLng = \Dz\Maps\Converter::fromAddressToLatLng($address);

echo 'Latitude: ', $latLng->lat, PHP_EOL;
echo 'Longitude: ', $latLng->lng, PHP_EOL;
```

You can use it to convert DMSs to decimals too:

``` php
<?php

// Cachoeira do Sul DMS latitude
$dmsLat = "30° 2' 54.0276'' S";
$decimalLat = \Dz\Maps\Converter::fromDmsToDecimal($dmsLat);

echo 'Latitude: ', $decimalLat, PHP_EOL;
```
