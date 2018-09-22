<?php declare(strict_types=1);

namespace RicardoFiorani\Legofy\Pallete;

use Intervention\Image\AbstractColor;
use Intervention\Image\Gd\Color;

class Palettes
{
    private static $legos = [
        //'solid' => [
        '024' => [0xfe, 0xc4, 0x01],
        '106' => [0xe7, 0x64, 0x19],
        '021' => [0xde, 0x01, 0x0e],
        '221' => [0xde, 0x38, 0x8b],
        '023' => [0x01, 0x58, 0xa8],
        '028' => [0x01, 0x7c, 0x29],
        '119' => [0x95, 0xb9, 0x0c],
        '192' => [0x5c, 0x1d, 0x0d],
        '018' => [0xd6, 0x73, 0x41],
        '001' => [0xf4, 0xf4, 0xf4],
        '026' => [0x02, 0x02, 0x02],
        '226' => [0xff, 0xff, 0x99],
        '222' => [0xee, 0x9d, 0xc3],
        '212' => [0x87, 0xc0, 0xea],
        '037' => [0x01, 0x96, 0x25],
        '005' => [0xd9, 0xbb, 0x7c],
        '283' => [0xf5, 0xc1, 0x89],
        '208' => [0xe4, 0xe4, 0xda],
        '191' => [0xf4, 0x9b, 0x01],
        '124' => [0x9c, 0x01, 0xc6],
        '102' => [0x48, 0x8c, 0xc6],
        '135' => [0x5f, 0x75, 0x8c],
        '151' => [0x60, 0x82, 0x66],
        '138' => [0x8d, 0x75, 0x53],
        '038' => [0xa8, 0x3e, 0x16],
        '194' => [0x9c, 0x92, 0x91],
        '154' => [0x80, 0x09, 0x1c],
        '268' => [0x2d, 0x16, 0x78],
        '140' => [0x01, 0x26, 0x42],
        '141' => [0x01, 0x35, 0x17],
        '312' => [0xaa, 0x7e, 0x56],
        '199' => [0x4d, 0x5e, 0x57],
        '308' => [0x31, 0x10, 0x07],
        //],

        //'transparent' => [
        '044' => [0xf9, 0xef, 0x69],
        '182' => [0xec, 0x76, 0x0e],
        '047' => [0xe7, 0x66, 0x48],
        '041' => [0xe0, 0x2a, 0x29],
        '113' => [0xee, 0x9d, 0xc3],
        '126' => [0x9c, 0x95, 0xc7],
        '042' => [0xb6, 0xe0, 0xea],
        '043' => [0x50, 0xb1, 0xe8],
        '143' => [0xce, 0xe3, 0xf6],
        '048' => [0x63, 0xb2, 0x6e],
        '311' => [0x99, 0xff, 0x66],
        '049' => [0xf1, 0xed, 0x5b],
        '111' => [0xa6, 0x91, 0x82],
        '040' => [0xee, 0xee, 0xee],
        //],

        //'effects' => [
        '131' => [0x8d, 0x94, 0x96],
        '297' => [0xaa, 0x7f, 0x2e],
        '148' => [0x49, 0x3f, 0x3b],
        '294' => [0xfe, 0xfc, 0xd5],
        //],

        //'mono' => [
        '001' => [0xf4, 0xf4, 0xf4],
        '026' => [0x02, 0x02, 0x02],
        //],
    ];

    /**
     *  Extend palette colors to 256 rgb sets.
     */
    public static function extend_palette($palette, $colors = 256, $rgb = 3)
    {
        $missing_colors = $colors - floor(count($palette) / $rgb);

        if ($missing_colors > 0) {
            $palette = array_merge($palette, $palette);
        }

        return array_slice($palette, 0, $colors * $rgb);
    }

    /**
     * Build flattened lego palettes.
     */
    public static function legos()
    {
        return self::flatten_palettes(self::$legos);
    }

    public static function getAllPalettes()
    {
        return self::$legos;
    }

    /**
     * Convert palette mappings into color list.
     */
    public static function flatten_palettes($palettes)
    {
        $flattened = [];
        $palettes = self::merge_palettes($palettes);

        foreach ($palettes as $paletteName => $palette) {
            foreach ($palette as $paletteHexColors) {
                foreach ($paletteHexColors as $hexColor) {
                    $flattened[$paletteName][] = $hexColor;
                }
            }
        }

        return $flattened;
    }

    /**
     * Build unified palette using all colors.
     */
    public static function merge_palettes($palettes)
    {
        $palettes['all'] = array_merge(
            $palettes['solid'],
            $palettes['transparent'],
            $palettes['effects'],
            $palettes['mono']
        );

        return $palettes;
    }

    public static function getClosest(Color $color): array
    {
        $palette = [
            '024' => [0 => 254, 1 => 196, 2 => 1,],
            '106' => [0 => 231, 1 => 100, 2 => 25,],
            '021' => [0 => 222, 1 => 1, 2 => 14,],
            '221' => [0 => 222, 1 => 56, 2 => 139,],
            '023' => [0 => 1, 1 => 88, 2 => 168,],
            '028' => [0 => 1, 1 => 124, 2 => 41,],
            '119' => [0 => 149, 1 => 185, 2 => 12,],
            '192' => [0 => 92, 1 => 29, 2 => 13,],
            '018' => [0 => 214, 1 => 115, 2 => 65,],
            '001' => [0 => 244, 1 => 244, 2 => 244,],
            '026' => [0 => 2, 1 => 2, 2 => 2,],
            '226' => [0 => 255, 1 => 255, 2 => 153,],
            '222' => [0 => 238, 1 => 157, 2 => 195,],
            '212' => [0 => 135, 1 => 192, 2 => 234,],
            '037' => [0 => 1, 1 => 150, 2 => 37,],
            '005' => [0 => 217, 1 => 187, 2 => 124,],
            '283' => [0 => 245, 1 => 193, 2 => 137,],
            '208' => [0 => 228, 1 => 228, 2 => 218,],
            '191' => [0 => 244, 1 => 155, 2 => 1,],
            '124' => [0 => 156, 1 => 1, 2 => 198,],
            '102' => [0 => 72, 1 => 140, 2 => 198,],
            '135' => [0 => 95, 1 => 117, 2 => 140,],
            '151' => [0 => 96, 1 => 130, 2 => 102,],
            '138' => [0 => 141, 1 => 117, 2 => 83,],
            '038' => [0 => 168, 1 => 62, 2 => 22,],
            '194' => [0 => 156, 1 => 146, 2 => 145,],
            '154' => [0 => 128, 1 => 9, 2 => 28,],
            '268' => [0 => 45, 1 => 22, 2 => 120,],
            '140' => [0 => 1, 1 => 38, 2 => 66,],
            '141' => [0 => 1, 1 => 53, 2 => 23,],
            '312' => [0 => 170, 1 => 126, 2 => 86,],
            '199' => [0 => 77, 1 => 94, 2 => 87,],
            '308' => [0 => 49, 1 => 16, 2 => 7,],
            '044' => [0 => 249, 1 => 239, 2 => 105,],
            '182' => [0 => 236, 1 => 118, 2 => 14,],
            '047' => [0 => 231, 1 => 102, 2 => 72,],
            '041' => [0 => 224, 1 => 42, 2 => 41,],
            '113' => [0 => 238, 1 => 157, 2 => 195,],
            '126' => [0 => 156, 1 => 149, 2 => 199,],
            '042' => [0 => 182, 1 => 224, 2 => 234,],
            '043' => [0 => 80, 1 => 177, 2 => 232,],
            '143' => [0 => 206, 1 => 227, 2 => 246,],
            '048' => [0 => 99, 1 => 178, 2 => 110,],
            '311' => [0 => 153, 1 => 255, 2 => 102,],
            '049' => [0 => 241, 1 => 237, 2 => 91,],
            '111' => [0 => 166, 1 => 145, 2 => 130,],
            '040' => [0 => 238, 1 => 238, 2 => 238,],
            '131' => [0 => 141, 1 => 148, 2 => 150,],
            '297' => [0 => 170, 1 => 127, 2 => 46,],
            '148' => [0 => 73, 1 => 63, 2 => 59,],
            '294' => [0 => 254, 1 => 252, 2 => 213,],
        ];

        $distances = [];

        foreach ($palette as $colorIdentifier => $colorSchema) {

            $distance =
                abs($colorSchema[0] - $color->r) +
                abs($colorSchema[1] - $color->g) +
                abs($colorSchema[2] - $color->b);

//            var_dump(
//                sprintf(
//                    'Distance from %s/%s/%s to %s/%s/%s = %s',
//                    $color->r, $color->g, $color->b,
//                    $colorSchema[0], $colorSchema[1], $colorSchema[2],
//                    $distance
//                )
//            );

            $distances[$distance] = [$colorSchema[0], $colorSchema[1], $colorSchema[2]];
        }

        ksort($distances);

        return reset($distances);
    }
}
