<?php declare(strict_types=1);

namespace Tests\RicardoFiorani\Unit\Palette;

use Intervention\Image\Gd\Color;
use PHPUnit\Framework\TestCase;
use RicardoFiorani\Legofy\Pallete\ColorPalette;

class ColorPaletteTest extends TestCase
{
    /**
     * @dataProvider colorProvider
     */
    public function testGetsRightColor(array $colors, array $input, $expected)
    {
        $colorPalette = new ColorPalette($colors);

        $outputColor = $colorPalette->pickClosestColor(new Color($input));
        $outputColorArray = $outputColor->getArray();

        $this->assertEquals($expected[0], $outputColorArray[0]);
        $this->assertEquals($expected[1], $outputColorArray[1]);
        $this->assertEquals($expected[2], $outputColorArray[2]);
    }

    public function colorProvider()
    {
        return [
            //Whole set
            [
                //Palette
                [
                    '024' => [254, 196, 1,],
                    '106' => [231, 100, 25,],
                    '021' => [222, 1, 14,],
                ],
                //input color
                [253, 194, 2],
                //expected color
                [254, 196, 1,]
            ],
            //Whole set
            [
                //Palette
                [
                    '024' => [254, 196, 1,],
                    '106' => [231, 100, 25,],
                    '021' => [222, 1, 14,],
                ],
                //input color
                [224, 5, 22],
                //expected color
                [222, 1, 14,]
            ],
        ];
    }
}