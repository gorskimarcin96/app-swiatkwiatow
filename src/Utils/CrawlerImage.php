<?php


namespace App\Utils;


use Symfony\Component\DomCrawler\Crawler;

class CrawlerImage implements CrawlerImageInterface
{
    public function getUniqueImagesFromHtmlContent(string $htmlContent): array
    {
        $images = $this->getImagesFromHtmlContent($htmlContent);

        return array_values(array_unique($images));
    }

    public function getImagesFromHtmlContent(string $htmlContent): array
    {
        $crawler = new Crawler($htmlContent);

        return $crawler->filter('img')->each(function (Crawler $node, $i) {
            return $node->attr('src');
        });
    }
}