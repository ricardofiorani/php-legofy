<?php declare(strict_types=1);

namespace Tests\RicardoFiorani\Integration;

use Dotenv\Dotenv;
use Imgur\Client;
use Intervention\Image\Image;
use PHPUnit\Framework\TestCase;
use RicardoFiorani\Legofy\Legofy;
use RicardoFiorani\Legofy\Pallete\LegoPaletteInterface;

class MainIntegrationTest extends TestCase
{
    private $client;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $dotenv = new Dotenv(__DIR__.'/../../');
        $dotenv->load();

        parent::__construct($name, $data, $dataName);
    }

    public function testMainFunctionality()
    {
        $legofier = new Legofy();

        TestCase::assertInstanceOf(Image::class, $legofier->getBrick());
        TestCase::assertInstanceOf(LegoPaletteInterface::class, $legofier->getPalette());

        $originalSource = __DIR__ . '/../../assets/examples/beer.jpg';
        $result = $legofier->convertToLego($originalSource);

        TestCase::assertInstanceOf(Image::class, $result);

        $this->uploadToImgur($result);

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

        fwrite(STDOUT, print_r($this->uploadToImgur($result), TRUE));

        TestCase::assertEquals(
            '5e1cdcdb0efedbc8e38bcf74e10f5378',
            md5($result->psrResponse()->getBody()->getContents())
        );
    }

    public function uploadToImgur(Image $image)
    {
        if (!$this->client) {
            $this->client = new Client();
            $this->client->setOption('client_id', getenv('IMGUR_CLIENT_ID'));
            $this->client->setOption('client_secret', getenv('IMGUR_CLIENT_SECRET'));
        }

        $imageData = [
            'image' => base64_encode($image->psrResponse(null, 100)->getBody()->getContents()),
            'type' => 'base64',
        ];

        $result = $this->client->api('image')->upload($imageData);

        return $result['link'];
    }
}