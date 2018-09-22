<?php

use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

require 'main.php';
//var_dump(
//main('flower.jpg')
//);

function getImageSource()
{
    $resource = isset($_GET['image']) ? $_GET['image'] : 'flower.jpg';
    $resourcePath = __DIR__ . '/temp/' . md5($resource);

    if (file_exists($resourcePath)) {
        return $resourcePath;
    }

    $downloaded = file_get_contents($resource);
    file_put_contents($resourcePath, $downloaded);

    return $resourcePath;
}

function getLegoBrickColor(\Intervention\Image\Gd\Color $color): \Intervention\Image\Gd\Color
{
    $brickColor = \RicardoFiorani\Legofy\Pallete\Palettes::getClosest($color);

    list($color->r, $color->g, $color->b) = $brickColor;

    return $color;
}

function showImages(Image $image1, Image $image2 = null, Image $image3 = null)
{
    $image2 = $image2 ?? ImageManagerStatic::canvas(1, 1);
    $image3 = $image3 ?? ImageManagerStatic::canvas(1, 1);

    $finalCanvas = ImageManagerStatic::canvas(
        $image1->getWidth() + $image2->getWidth() + $image3->getWidth(),
        max($image1->getHeight(), $image2->getHeight() ?? 0, $image3->getHeight() ?? 0)
    );

    $finalCanvas->insert($image1, 'left-center');
    $finalCanvas->insert($image2, 'center');
    $finalCanvas->insert($image3, 'right-center');

    die($finalCanvas->response(null, 100));
}


error_reporting(E_ALL);
ini_set("display_errors", "on");
ini_set("max_execution_time", "60");

$resolution = isset($_GET['res']) ? $_GET['res'] : 1;

$brick = ImageManagerStatic::make('1x1.png');
$brickColor = (clone $brick)
    ->pixelate($brick->getWidth())
    ->blur(50)
    ->pickColor($brick->width() / 2, $brick->getHeight() / 2, 'obj');


$useLegoPalette = isset($_GET['lego']) ? $_GET['lego'] : 0;

$imageSource = getImageSource();
$image = ImageManagerStatic::make($imageSource);

$imageOriginalWidth = $image->width();
$imageOriginalHeight = $image->height();

$image->resize($image->getWidth() * $resolution, $image->getHeight() * $resolution);
//die($image->response());

$brickWidth = $brick->getWidth();
$brickHeight = $brick->getHeight();

//$image->blur(50);
//$image->pixelate($brickWidth);

$amountOfBricksX = round($image->getWidth() / $brickWidth);
$amountOfBricksY = round($image->getHeight() / $brickHeight);


//var_dump($amountOfBricksX, $amountOfBricksY);
//die;

$canvas = ImageManagerStatic::canvas($amountOfBricksX * $brickWidth, $amountOfBricksY * $brickHeight);
//$canvas = ImageManagerStatic::canvas(1000, 1000);

$image->resize($amountOfBricksX * $brickWidth, $amountOfBricksY * $brickHeight);

foreach (range(0, $amountOfBricksX) as $x) {
    foreach (range(0, $amountOfBricksY) as $y) {
        $positionX = $x * $brickWidth;
        $positionY = $y * $brickHeight;

        if ($positionX == $image->getWidth()) {
            $positionX--;
        }

        if ($positionY == $image->getHeight()) {
            $positionY--;
        }

        $color = $image->pickColor($positionX, $positionY, 'object');

        if ($useLegoPalette) {
            $color = getLegoBrickColor($color);
        }

        $colorizedBrick = ImageManagerStatic::canvas($brickWidth, $brickHeight, $color->getHex())
            ->insert((clone $brick)
                ->colorize(
                    ($color->r - $brickColor->r) / 2.55,
                    ($color->g - $brickColor->g) / 2.55,
                    ($color->b - $brickColor->b) / 2.55
                )
            );

        //showImages($colorizedBrick, null, ImageManagerStatic::canvas($brickWidth, $brickHeight, $originalColor->getHex()));

        $canvas->insert(
            $colorizedBrick,
            '',
            ($x * $brickWidth) - $brickWidth,
            ($y * $brickWidth) - $brickWidth
        );
    }
}


$original = ImageManagerStatic::make($imageSource);
$canvas->resize($imageOriginalWidth, $imageOriginalHeight);

showImages(
    $original,
    null,
    //$image,
    $canvas
);

//die($canvas->response());


