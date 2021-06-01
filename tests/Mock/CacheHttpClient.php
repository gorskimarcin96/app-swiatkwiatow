<?php


namespace App\Tests\Mock;


use App\Utils\CacheHttpClientInterface;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class CacheHttpClient implements CacheHttpClientInterface
{
    private array $responses = [];

    /**
     * @param string $url
     * @return string
     * @throws InvalidArgumentException
     */
    public function getResponseFromUrl(string $url): string
    {
        if (isset($this->responses[md5($url)])) {
            $this->createCacheResponse($url);

            return $this->responses[md5($url)];
        }

        throw new Exception('No mocked response for such request.');
    }

    /**
     * @param string $url
     * @param string $response
     */
    public function addResponse(string $url, string $response): void
    {
        $this->responses[md5($url)] = $response;
    }

    /**
     * @param string $url
     * @throws InvalidArgumentException
     */
    private function createCacheResponse(string $url): void
    {
        $filesystemAdapter = new FilesystemAdapter();

        $cacheItem = $filesystemAdapter->getItem(\App\Utils\CacheHttpClient::CACHE_PREFIX . '.response.' . md5($url));
        $cacheItem->set($this->responses[md5($url)]);

        $filesystemAdapter->save($cacheItem);
    }
}