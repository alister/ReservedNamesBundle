<?php

define('TESTS_PATH', __DIR__);
define('VENDOR_PATH', realpath(__DIR__.'/../vendor'));

if (!class_exists('PHPUnit_Framework_TestCase') ||
    version_compare(PHPUnit_Runner_Version::id(), '3.5') < 0
) {
    die('PHPUnit framework is required, at least 3.5 version');
}

if (!($loader = @include __DIR__.'/../vendor/autoload.php')) {
    echo <<<'EOT'
You need to install the project dependencies using Composer:
$ wget http://getcomposer.org/composer.phar
OR
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install --dev
$ phpunit

EOT;
    exit(1);
}

$loader->add('Alister\\ReservedNamesBundle', '../src/ReservedNamesBundle');
