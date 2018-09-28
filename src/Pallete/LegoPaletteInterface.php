<?php declare(strict_types=1);

namespace RicardoFiorani\Legofy\Pallete;

use Intervention\Image\AbstractColor;

interface LegoPaletteInterface
{
    public function pickClosestColor(AbstractColor $color): AbstractColor;
}
