<?php declare(strict_types=1);

namespace RicardoFiorani\Legofy;

use Intervention\Image\AbstractColor;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;
use RicardoFiorani\Legofy\Pallete\ColorPalette;
use RicardoFiorani\Legofy\Pallete\LegoPaletteInterface;

class Legofy
{
    /**
     * @var Image
     */
    private $brick;

    /**
     * @var AbstractColor
     */
    private $brickAverageColor;

    /**
     * @var int
     */
    private $brickWidth;

    /**
     * @var int
     */
    private $brickHeight;

    /**
     * @var LegoPaletteInterface
     */
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
        $this->brick       = $brick;
        $this->brickWidth  = $brick->getWidth();
        $this->brickHeight = $brick->getHeight();

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

        // Calculate how many bricks fit in the image
        $amountOfBricksX = round($image->getWidth() * $resolutionMultipler / $this->brickWidth);
        $amountOfBricksY = round($image->getHeight() * $resolutionMultipler / $this->brickHeight);

        // Resize to the rounded value relative to the brick size
        $image->resize(
            $amountOfBricksX * $this->brickWidth,
            $amountOfBricksY * $this->brickHeight
        );

        $canvas = ImageManagerStatic::canvas(
            $amountOfBricksX * $this->brickWidth,
            $amountOfBricksY * $this->brickHeight
        );

        for ($x = 0; $x < $amountOfBricksX; ++$x) {
            for ($y = 0; $y < $amountOfBricksY; ++$y) {
                $positionX = $x * $this->brickWidth;
                $positionY = $y * $this->brickHeight;

                /** @var AbstractColor $color */
                $color = $image->pickColor($positionX, $positionY, 'object');

                if ($legoColorsOnly) {
                    $color = $this->palette->pickClosestColor($color);
                }

                $colorizedBrick = $this->colorizeBrick($color);

                $canvas->insert(
                    $colorizedBrick,
                    '',
                    $x * $this->brickWidth,
                    $y * $this->brickHeight
                );
            }
        }

        return $canvas;
    }

    private function getAverageBrickColor(): AbstractColor
    {
        if (false === is_null($this->brickAverageColor)) {
            return $this->brickAverageColor;
        }

        return $this->brickAverageColor = (clone $this->getBrick())
            ->pixelate($this->brickWidth)
            ->blur(50)
            ->pickColor($this->brickWidth / 2, $this->brickHeight / 2, 'obj');
    }

    private function colorizeBrick(AbstractColor $color): Image
    {
        $brickColor = $this->getAverageBrickColor();

        $colorRgba = $color->getArray();
        $brickColorRgba = $brickColor->getArray();

        return ImageManagerStatic::canvas(
            $this->getBrick()->getWidth(),
            $this->brickHeight,
            $color->getHex('')
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
