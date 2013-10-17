<?php

// Ensure that composer has installed all dependencies.
// Copied from https://github.com/guzzle/guzzle/blob/master/tests/bootstrap.php.
if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die("Dependencies must be installed using composer:\n\nphp composer.phar install --dev\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}

require_once __DIR__ . '/../vendor/autoload.php';
