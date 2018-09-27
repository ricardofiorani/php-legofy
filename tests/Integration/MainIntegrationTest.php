<?php declare(strict_types=1);

namespace Tests\RicardoFiorani\Integration;

use Intervention\Image\Image;
use PHPUnit\Framework\TestCase;
use RicardoFiorani\Legofy\Legofy;
use RicardoFiorani\Legofy\Pallete\LegoPaletteInterface;

class MainIntegrationTest extends TestCase
{
    public function testMainFunctionality()
    {
        $legofier = new Legofy();

        TestCase::assertInstanceOf(Image::class, $legofier->getBrick());
        TestCase::assertInstanceOf(LegoPaletteInterface::class, $legofier->getPalette());

        $originalSource = __DIR__ . '/../../assets/examples/beer.jpg';
        $result = $legofier->convertToLego($originalSource);

        TestCase::assertInstanceOf(Image::class, $result);

        TestCase::assertEquals(
            '83d6270ee6470a3a93641e06485181ef',
            md5($result->psrResponse()->getBody()->getContents())
        );
    }

    public function testWorksOnLegoColorOnly()
    {
        $legofier = new Legofy();

        TestCase::assertInstanceOf(Image::class, $legofier->getBrick());
        TestCase::assertInstanceOf(LegoPaletteInterface::class, $legofier->getPalette());

        $originalSource = __DIR__ . '/../../assets/examples/beer.jpg';
        $result = $legofier->convertToLego($originalSource, 1, true);

        TestCase::assertInstanceOf(Image::class, $result);

        TestCase::assertEquals(
            '5e1cdcdb0efedbc8e38bcf74e10f5378',
            md5($result->psrResponse()->getBody()->getContents())
        );
    }
}