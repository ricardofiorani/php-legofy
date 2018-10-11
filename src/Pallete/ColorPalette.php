<?php declare(strict_types=1);

namespace RicardoFiorani\Legofy\Pallete;

use Intervention\Image\AbstractColor;

class ColorPalette implements LegoPaletteInterface
{
    private const PALETTE = [
        '024' => [
            254,
            196,
            1,
        ],
        '106' => [
            231,
            100,
            25,
        ],
        '021' => [
            222,
            1,
            14,
        ],
        '221' => [
            222,
            56,
            139,
        ],
        '023' => [
            1,
            88,
            168,
        ],
        '028' => [
            1,
            124,
            41,
        ],
        '119' => [
            149,
            185,
            12,
        ],
        '192' => [
            92,
            29,
            13,
        ],
        '018' => [
            214,
            115,
            65,
        ],
        '001' => [
            244,
            244,
            244,
        ],
        '026' => [
            2,
            2,
            2,
        ],
        '226' => [
            255,
            255,
            153,
        ],
        '222' => [
            238,
            157,
            195,
        ],
        '212' => [
            135,
            192,
            234,
        ],
        '037' => [
            1,
            150,
            37,
        ],
        '005' => [
            217,
            187,
            124,
        ],
        '283' => [
            245,
            193,
            137,
        ],
        '208' => [
            228,
            228,
            218,
        ],
        '191' => [
            244,
            155,
            1,
        ],
        '124' => [
            156,
            1,
            198,
        ],
        '102' => [
            72,
            140,
            198,
        ],
        '135' => [
            95,
            117,
            140,
        ],
        '151' => [
            96,
            130,
            102,
        ],
        '138' => [
            141,
            117,
            83,
        ],
        '038' => [
            168,
            62,
            22,
        ],
        '194' => [
            156,
            146,
            145,
        ],
        '154' => [
            128,
            9,
            28,
        ],
        '268' => [
            45,
            22,
            120,
        ],
        '140' => [
            1,
            38,
            66,
        ],
        '141' => [
            1,
            53,
            23,
        ],
        '312' => [
            170,
            126,
            86,
        ],
        '199' => [
            77,
            94,
            87,
        ],
        '308' => [
            49,
            16,
            7,
        ],
        '044' => [
            249,
            239,
            105,
        ],
        '182' => [
            236,
            118,
            14,
        ],
        '047' => [
            231,
            102,
            72,
        ],
        '041' => [
            224,
            42,
            41,
        ],
        '113' => [
            238,
            157,
            195,
        ],
        '126' => [
            156,
            149,
            199,
        ],
        '042' => [
            182,
            224,
            234,
        ],
        '043' => [
            80,
            177,
            232,
        ],
        '143' => [
            206,
            227,
            246,
        ],
        '048' => [
            99,
            178,
            110,
        ],
        '311' => [
            153,
            255,
            102,
        ],
        '049' => [
            241,
            237,
            91,
        ],
        '111' => [
            166,
            145,
            130,
        ],
        '040' => [
            238,
            238,
            238,
        ],
        '131' => [
            141,
            148,
            150,
        ],
        '297' => [
            170,
            127,
            46,
        ],
        '148' => [
            73,
            63,
            59,
        ],
        '294' => [
            254,
            252,
            213,
        ],
    ];

    /**
     * @link https://stackoverflow.com/questions/4485229/rgb-to-closest-predefined-color
     */
    public function pickClosestColor(AbstractColor $color): AbstractColor
    {
        $distances = [];

        $colorArray = $color->getArray();

        foreach (self::PALETTE as $colorIdentifier => $colorSchema) {
            $rDistance = $colorSchema[0] - $colorArray[0];
            $gDistance = $colorSchema[1] - $colorArray[1];
            $bDistance = $colorSchema[2] - $colorArray[2];

            $distance = ($rDistance * .299) ** 2 + ($gDistance * .587) ** 2 + ($bDistance * .114) ** 2;

            $distances[$colorIdentifier] = $distance;
        }

        asort($distances);

        $colorIdentifier = array_keys($distances)[0];

        $color->initFromRgb(...self::PALETTE[$colorIdentifier]);

        return $color;
    }
}
