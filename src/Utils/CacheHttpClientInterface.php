<?php


namespace App\Utils;


interface CacheHttpClientInterface
{
    public function getResponseFromUrl(string $url): string;
}