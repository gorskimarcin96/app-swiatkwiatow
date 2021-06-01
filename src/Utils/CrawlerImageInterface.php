<?php


namespace App\Utils;


interface CrawlerImageInterface
{
    public function getUniqueImagesFromHtmlContent(string $htmlContent): array;

    public function getImagesFromHtmlContent(string $htmlContent): array;
}