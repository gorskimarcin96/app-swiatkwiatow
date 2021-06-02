<?php

namespace App\Tests\Utils\CrawlerImage;

use App\Utils\CrawlerImage\CrawlerImage;
use PHPUnit\Framework\TestCase;

class CrawlerImageTest extends TestCase
{
    private CrawlerImage $crawlerImage;
    private string $testView;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->crawlerImage = new CrawlerImage();
        $this->testView = file_get_contents(__DIR__ . '/test_image_crawler_view.html');
    }

    /**
     * @return void
     */
    public function testGetImagesFromHtmlContent(): void
    {
        $response = $this->crawlerImage->getImagesFromHtmlContent($this->testView);

        self::assertSame([
            '/test/path/logo.png',
            '/test/path/logo.png',
            '/test/path/image1.png',
            '/test/path/image2.png',
            '/test/path/image3.png',
            '/test/path/logo.png',
        ], $response);
    }

    /**
     * @return void
     */
    public function testGetUniqueImagesFromHtmlContent(): void
    {
        $response = $this->crawlerImage->getUniqueImagesFromHtmlContent($this->testView);

        self::assertSame([
            '/test/path/logo.png',
            '/test/path/image1.png',
            '/test/path/image2.png',
            '/test/path/image3.png',
        ], $response);
    }
}
