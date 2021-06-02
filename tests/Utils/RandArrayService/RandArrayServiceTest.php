<?php

namespace App\Tests\Utils\RandArrayService;

use App\Utils\RandArrayService\RandArrayService;
use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;

class RandArrayServiceTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testRandNotRepeatValues(): void
    {
        foreach (range(0, 1) as $loop) {
            $testData = range(0, 9);
            $randArrayService = new RandArrayService($testData);

            $randData[] = $randArrayService->randNotRepeatValues(4);
            self::assertCount(4, $randData[0]);
            foreach ($randData[0] as $randDatum) {
                self::assertContains($randDatum, $testData);
            }

            $randData[] = $randArrayService->randNotRepeatValues(4);
            self::assertCount(4, $randData[1]);
            foreach ($randData[1] as $randDatum) {
                self::assertContains($randDatum, $testData);
                self::assertNotContains($randDatum, $randData[0]);
            }

            $randData[] = $randArrayService->randNotRepeatValues(4);
            self::assertCount(2, $randData[2]);
            foreach ($randData[2] as $randDatum) {
                self::assertContains($randDatum, $testData);
                self::assertNotContains($randDatum, $randData[0]);
                self::assertNotContains($randDatum, $randData[1]);
            }
        }
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    public function testTwoArrayRandNotRepeatValues(): void
    {
        $firstTestData = ['qwe', 'asd', 'zxc'];
        $secondTestData = ['qwe', 'asd', 'zxc', '123'];

        foreach (range(0, 1) as $loop) {
            foreach ([$firstTestData, $secondTestData] as $key => $testData) {
                $randArrayService = new RandArrayService($testData);
                $result = $randArrayService->randNotRepeatValues(2);
                self::assertCount(!($loop % 2) && count($testData) === 3 ? 1 : 2, $result);
            }
        }
    }
}
