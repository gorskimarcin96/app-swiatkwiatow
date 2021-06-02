<?php


namespace App\Utils\CrawlerImage;


interface CrawlerImageInterface
{
    /**
     * @param string $htmlContent
     * @return array
     */
    public function getUniqueImagesFromHtmlContent(string $htmlContent): array;

    /**
     * @param string $htmlContent
     * @return array
     */
    public function getImagesFromHtmlContent(string $htmlContent): array;
}