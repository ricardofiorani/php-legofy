# PHP Legofy
[![Build Status](https://api.travis-ci.org/ricardofiorani/php-legofy.svg?branch=master)](http://travis-ci.org/ricardofiorani/php-legofy)
[![Minimum PHP Version](https://img.shields.io/packagist/php-v/ricardofiorani/php-legofy.svg)](https://php.net/)
[![License](https://poser.pugx.org/ricardofiorani/php-legofy/license.png)](https://packagist.org/packages/ricardofiorani/php-legofy)
[![Total Downloads](https://poser.pugx.org/ricardofiorani/php-legofy/d/total.png)](https://packagist.org/packages/ricardofiorani/php-legofy)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--4-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ricardofiorani/php-legofy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ricardofiorani/php-legofy/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ricardofiorani/php-legofy/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ricardofiorani/php-legofy/?branch=master)

### What is this ?
PHP Legofy is a PHP package that takes a static image and makes it so that it looks as if it was built out of LEGO.  
It was inspired by the original Legofy made in Python https://github.com/JuanPotato/Legofy

Basically it transforms this:
![Image of a beer][beer]
Into this:
![Image of a legofied beer][lego-beer]

[beer]: ./assets/examples/beer.jpg
[lego-beer]: ./assets/examples/lego-beer.jpeg

### Requirements
* PHP 7.1 (or above)
* GD or ImageMagick
> I tested it with GD only but I'm trusting intervention/image that this will work on ImageMagick as well.

### Quickstart
Via composer:
```bash
$ composer require ricardofiorani/php-legofy
```

Via source:
```bash
$ git clone git@github.com:ricardofiorani/php-legofy.git
$ cd php-legofy
$ composer install
```

### Usage:
```php
<?php
require 'vendor/autoload.php';

// The multiplier for the amount of legos on your image, or "legolution" :)
$resolutionMultiplier = 1;

// When set to true it will only use lego colors that exists in real world.
$useLegoPalette = false;

$legofy = new \RicardoFiorani\Legofy\Legofy();

// $source can be any acceptable parameter for intervention/image
// Please see http://image.intervention.io/api/make
$source = 'my/imagem/path/image.jpg';

/**
 *@var Intervention\Image\Image 
 */
$output = $legofy->convertToLego($source, $resolutionMultiplier, $useLegoPalette);

// Please see http://image.intervention.io/use/basics and http://image.intervention.io/use/http
echo $output->response();
```

For more examples of usage please see [the usage examples on documentation](https://github.com/ricardofiorani/php-legofy/blob/master/docs/EXAMPLES.md)