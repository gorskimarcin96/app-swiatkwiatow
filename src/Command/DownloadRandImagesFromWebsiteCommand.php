<?php

namespace App\Command;

use App\Utils\CacheHttpClient;
use App\Utils\CacheHttpClientInterface;
use App\Utils\CrawlerImage;
use App\Utils\CrawlerImageInterface;
use App\Utils\RandArrayService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DownloadRandImagesFromWebsiteCommand extends Command
{
    protected static $defaultName = 'app:download-rand-images-from-website';
    protected static $defaultDescription = 'Add a short description for your command';
    private CacheHttpClientInterface $cacheHttpClient;
    private CrawlerImageInterface $crawlerImageService;
    private LoggerInterface $logger;

    /**
     * DownloadRandImagesFromWebsiteCommand constructor.
     * @param LoggerInterface $logger
     * @param CacheHttpClientInterface $cacheHttpClient
     * @param CrawlerImageInterface $crawlerImageService
     */
    public function __construct(
        LoggerInterface $logger,
        CacheHttpClientInterface $cacheHttpClient,
        CrawlerImageInterface $crawlerImageService
    )
    {
        parent::__construct();
        $this->cacheHttpClient = $cacheHttpClient;
        $this->crawlerImageService = $crawlerImageService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('website', InputArgument::OPTIONAL, 'website url', 'https://sklep.swiatkwiatow.pl/')
            ->addArgument('randImages', InputArgument::OPTIONAL, 'number rand images', 3);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $website = $input->getArgument('website');
        $numberRandImages = $input->getArgument('randImages');

        try {
            $response = $this->cacheHttpClient->getResponseFromUrl($website);
            $images = $this->crawlerImageService->getUniqueImagesFromHtmlContent($response);

            $randService = new RandArrayService($images);
            $randImages = $randService->randNotRepeatValues($numberRandImages);

            $io->info($randImages);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());
        }

        return Command::SUCCESS;
    }
}
