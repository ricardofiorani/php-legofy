<?php

use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

require 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", "on");
ini_set("max_execution_time", "60");

$brick = $_GET['brick'] ?? null;
$resolutionMultiplier = $_GET['res'] ?? 1;
$useLegoPalette = (bool)($_GET['lego'] ?? 0);

$legofy = new \RicardoFiorani\Legofy\Legofy($brick);

$output = $legofy->convertToLego(getImageSource(), $resolutionMultiplier, $useLegoPalette);

showImages(ImageManagerStatic::make(getImageSource()), null, $output);

die();

function getImageSource()
{
    $resource = $_GET['image'] ?? __DIR__ . '/flower.jpg';
    $resourcePath = __DIR__ . '/temp/' . md5($resource);

    if (file_exists($resourcePath)) {
        return $resourcePath;
    }

    $downloaded = file_get_contents($resource);
    file_put_contents($resourcePath, $downloaded);

    return $resourcePath;
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