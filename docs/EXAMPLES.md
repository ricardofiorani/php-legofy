# Examples

### Input Image:
![Image of a beer][beer]

### Example 1 - Default:
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
Output:
![Image of a legofied beer][lego-beer]

### Example 2 - Resolution multiplier
> Please be advised that using higher multiplers will consume more memory and CPU usage on the legofying process !  

The resolution multiplier multiplies the resolution of the original source image. The default value is 1.
Please note that this value is a float, so you can set it as 1.5 for example:
![Image of a legofied beer with more res][1-5-lego-beer]
You can also set it as an 0.5 value for example:
![Image of a legofied beer with less res][0-5-lego-beer]

### Example 3 - Using the original lego color palette
On this package I also added the feature to use the [original LEGO color palette](https://github.com/JuanPotato/Legofy/blob/master/2010-LEGO-color-palette.pdf). (The same that [https://github.com/JuanPotato/Legofy](https://github.com/JuanPotato/Legofy) uses.)
```php
<?php

$legofy->convertToLego($source, $resolutionMultiplier, true);

```
Output:
![Image of a legofied beer][real-color-lego-beer]

> Please note that this implementation of color distance uses the [Euclidean method](https://en.wikipedia.org/wiki/Color_difference#Euclidean) so it might not be 100% precise for scientific purposes.


[beer]: ../assets/examples/beer.jpg
[lego-beer]: ../assets/examples/lego-beer.jpeg
[1-5-lego-beer]: ../assets/examples/res-1-5-lego-beer.jpeg
[0-5-lego-beer]: ../assets/examples/res-0-5-lego-beer.jpeg
[real-color-lego-beer]: ../assets/examples/real-color-lego-beer.jpeg