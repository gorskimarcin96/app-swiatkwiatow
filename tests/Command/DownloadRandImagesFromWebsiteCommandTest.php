<?php

namespace App\Tests\Command;

use App\Command\DownloadRandImagesFromWebsiteCommand;
use App\Utils\CacheHttpClient\CacheHttpClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DownloadRandImagesFromWebsiteCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();
        $this->prepareResponseForCacheHttpClientMock($kernel);

        $application = new Application($kernel);
        $command = $application->find('app:download-rand-images-from-website');
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @param KernelInterface $kernel
     */
    private function prepareResponseForCacheHttpClientMock(KernelInterface $kernel): void
    {
        $view = '<div>
            <img src="/mock/path/image1.png" alt="fake image 1">
            <img src="/mock/path/image2.png" alt="fake image 2">
            <img src="/mock/path/image3.png" alt="fake image 3">
            <img src="/mock/path/image4.png" alt="fake image 4">
            <img src="/mock/path/image5.png" alt="fake image 5">
            <img src="/mock/path/image6.png" alt="fake image 6">
            <img src="/mock/path/image7.png" alt="fake image 7">
        </div>';

        $cacheHttpClient = $kernel->getContainer()->get(CacheHttpClientInterface::class);
        $cacheHttpClient->addResponse('https://test.url', $view);
        $kernel->getContainer()->set(CacheHttpClientInterface::class, $cacheHttpClient);
    }

    /**
     * @param string $response
     * @return array
     */
    private function getArrayStringsResultFromCommand(string $response): array
    {
        $output = explode("\n", str_replace('[INFO]', '', $response));
        $output = array_map('trim', $output);

        return array_filter($output, static fn($value) => $value !== '');
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        $testValues = [
            '/mock/path/image1.png',
            '/mock/path/image2.png',
            '/mock/path/image3.png',
            '/mock/path/image4.png',
            '/mock/path/image5.png',
            '/mock/path/image6.png',
            '/mock/path/image7.png',
        ];


        //test get first 5 images
        $this->commandTester->execute([
            'website' => 'https://test.url',
            'randImages' => 5,
        ]);
        $output = $this->getArrayStringsResultFromCommand($this->commandTester->getDisplay());
        self::assertCount(5, $output);
        foreach ($output as $string) {
            self::assertContains($string, $testValues);
        }

        //test get last images
        $this->commandTester->execute([
            'website' => 'https://test.url',
            'randImages' => 5,
        ]);
        $output = $this->getArrayStringsResultFromCommand($this->commandTester->getDisplay());
        self::assertCount(2, $output);
        foreach ($output as $string) {
            self::assertContains($string, $testValues);
        }
    }
}
