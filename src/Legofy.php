<?php declare(strict_types=1);

namespace RicardoFiorani\Legofy;

use Intervention\Image\AbstractColor;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;
use RicardoFiorani\Legofy\Pallete\ColorPalette;
use RicardoFiorani\Legofy\Pallete\LegoPaletteInterface;

class Legofy
{
    private $brick;
    private $brickAverageColor;
    private $palette;

    public function __construct($brickResource = null, LegoPaletteInterface $palette = null)
    {
        $this->setBrick(
            ImageManagerStatic::make($brickResource ?? __DIR__ . '/../assets/brick.png')
        );

        $this->setPalette($palette ?? new ColorPalette());
    }

    public function setBrick(Image $brick): self
    {
        $this->brick = $brick;

        return $this;
    }

    public function setPalette(LegoPaletteInterface $palette): self
    {
        $this->palette = $palette;

        return $this;
    }

    public function getBrick(): Image
    {
        return $this->brick;
    }

    public function getPalette(): LegoPaletteInterface
    {
        return $this->palette;
    }

    public function convertToLego($resource, float $resolutionMultipler = 1, bool $legoColorsOnly = false): Image
    {
        $image = ImageManagerStatic::make($resource);

        $imageOriginalWidth = $image->width();
        $imageOriginalHeight = $image->height();

        // Apply resolution multipler
        $image->resize(
            $image->getWidth() * $resolutionMultipler,
            $image->getHeight() * $resolutionMultipler
        );

        // Calculate how many bricks fit in the image
        $amountOfBricksX = round($image->getWidth() / $this->getBrick()->getWidth());
        $amountOfBricksY = round($image->getHeight() / $this->getBrick()->getHeight());

        // Resize to the rounded value relative to the brick size
        $image->resize(
            $amountOfBricksX * $this->getBrick()->getWidth(),
            $amountOfBricksY * $this->getBrick()->getHeight()
        );

        $canvas = ImageManagerStatic::canvas(
            $amountOfBricksX * $this->getBrick()->getWidth(),
            $amountOfBricksY * $this->getBrick()->getHeight()
        );

        foreach (range(0, $amountOfBricksX) as $x) {
            foreach (range(0, $amountOfBricksY) as $y) {
                $positionX = $x * $this->getBrick()->getWidth();
                $positionY = $y * $this->getBrick()->getHeight();

                if ($positionX == $image->getWidth()) {
                    $positionX--;
                }

                if ($positionY == $image->getHeight()) {
                    $positionY--;
                }

                $color = $image->pickColor($positionX, $positionY, 'object');

                if ($legoColorsOnly) {
                    $color = $this->palette->pickClosestColor($color);
                }

                $colorizedBrick = $this->colorizeBrick($color);

                $canvas->insert(
                    $colorizedBrick,
                    '',
                    ($x * $this->getBrick()->getWidth()) - $this->getBrick()->getWidth(),
                    ($y * $this->getBrick()->getHeight()) - $this->getBrick()->getHeight()
                );
            }
        }

        return $canvas;
    }

    private function getAverageBrickColor(): AbstractColor
    {
        if (false == is_null($this->brickAverageColor)) {
            return $this->brickAverageColor;
        }

        return $this->brickAverageColor = (clone $this->getBrick())
            ->pixelate($this->getBrick()->getWidth())
            ->blur(50)
            ->pickColor($this->getBrick()->width() / 2, $this->getBrick()->getHeight() / 2, 'obj');
    }

    private function colorizeBrick(AbstractColor $color): Image
    {
        $brickColor = $this->getAverageBrickColor();

        $colorRgba = $color->getArray();
        $brickColorRgba = $brickColor->getArray();

        return ImageManagerStatic::canvas(
            $this->getBrick()->getWidth(),
            $this->getBrick()->getHeight(),
            $color->getHex()
        )->insert(
            (clone $this->getBrick())->colorize(
                // Picked color subtracted by the average brick color to avoid the image getting brighter
                ($colorRgba[0] - $brickColorRgba[1]) / 2.55,
                ($colorRgba[1] - $brickColorRgba[1]) / 2.55,
                ($colorRgba[2] - $brickColorRgba[2]) / 2.55
            )
        );
    }
}
