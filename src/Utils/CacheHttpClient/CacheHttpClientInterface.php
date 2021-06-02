<?php


namespace App\Utils\CacheHttpClient;


interface CacheHttpClientInterface
{
    /**
     * @param string $url
     * @return string
     */
    public function getResponseFromUrl(string $url): string;
}