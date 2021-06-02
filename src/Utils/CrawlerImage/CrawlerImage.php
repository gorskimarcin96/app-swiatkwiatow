<?php


namespace App\Utils\CrawlerImage;


use Symfony\Component\DomCrawler\Crawler;

class CrawlerImage implements CrawlerImageInterface
{
    /**
     * @param string $htmlContent
     * @return array
     */
    public function getUniqueImagesFromHtmlContent(string $htmlContent): array
    {
        $images = $this->getImagesFromHtmlContent($htmlContent);

        return array_values(array_unique($images));
    }

    /**
     * @param string $htmlContent
     * @return array
     */
    public function getImagesFromHtmlContent(string $htmlContent): array
    {
        $crawler = new Crawler($htmlContent);

        return $crawler->filter('img')->each(function (Crawler $node, $i) {
            return $node->attr('src');
        });
    }
}