<?php

namespace App\Tests\Utils\CacheHttpClient;

use App\Utils\CacheHttpClient\CacheHttpClient;
use App\Utils\CacheHttpClient\CacheHttpClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class CacheHttpClientTest extends KernelTestCase
{
    /**
     * @var CacheHttpClient|object|null
     */
    private $cacheHttpClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheHttpClient = self::bootKernel()->getContainer()->get(CacheHttpClientInterface::class);
        $this->cacheHttpClient->addResponse('test.url.1', 'test.response.1');
        $this->cacheHttpClient->addResponse('test.url.2', 'test.response.2');
    }

    /**
     * @throws InvalidArgumentException
     * @return void
     */
    public function testGetResponseFromUrl(): void
    {
        $response = $this->cacheHttpClient->getResponseFromUrl('test.url.1');

        self::assertSame('test.response.1', $response);
    }

    /**
     * @throws InvalidArgumentException
     * @return void
     */
    public function testGenerateCache(): void
    {
        $this->cacheHttpClient->getResponseFromUrl('test.url.2');
        $filesystemAdapter = new FilesystemAdapter();
        /** @var CacheItem $cacheItem */
        $cacheItem = $filesystemAdapter->getItem(CacheHttpClient::CACHE_PREFIX . '.response.' . md5('test.url.2'));

        self::assertSame('test.response.2', $cacheItem->get());
    }
}
