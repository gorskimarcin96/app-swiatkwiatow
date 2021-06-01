<?php


namespace App\Utils;


use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CacheHttpClient implements CacheHttpClientInterface
{
    public const CACHE_PREFIX = 'cache_http_client';
    private HttpClientInterface $httpClient;
    private FilesystemAdapter $filesystemAdapter;

    /**
     * HttpClient constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->filesystemAdapter = new FilesystemAdapter();
    }

    /**
     * @param string $url
     * @return string
     * @throws InvalidArgumentException
     */
    public function getResponseFromUrl(string $url): string
    {
        return $this->filesystemAdapter->get($this->getCacheKey($url), function (ItemInterface $item) use ($url) {
            $item->expiresAfter(3600);

            return $this->httpClient->request('GET', $url)->getContent(false);
        });
    }

    /**
     * @param string $url
     * @return string
     */
    private function getCacheKey(string $url): string
    {
        return self::CACHE_PREFIX . '.response.' . md5($url);
    }
}