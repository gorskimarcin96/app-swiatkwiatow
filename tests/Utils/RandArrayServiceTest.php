<?php

namespace App\Tests\Utils;

use App\Utils\RandArrayService;
use PHPUnit\Framework\TestCase;

class RandArrayServiceTest extends TestCase
{
    public function testRandNotRepeatValues(): void
    {
        foreach (range(0, 1) as $loop) {
            $testData = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
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

    public function testTwoArrayRandNotRepeatValues(): void
    {
        $firstTestData = ['qwe', 'asd', 'zxc'];
        $secondTestData = ['qwe', 'asd', 'zxc', '123'];

        $randArrayService = new RandArrayService($firstTestData);
        $result = $randArrayService->randNotRepeatValues(2);
        self::assertCount(2, $result);

        $randArrayService = new RandArrayService($secondTestData);
        $result = $randArrayService->randNotRepeatValues(2);
        self::assertCount(2, $result);

        $randArrayService = new RandArrayService($firstTestData);
        $result = $randArrayService->randNotRepeatValues(2);
        self::assertCount(1, $result);

        $randArrayService = new RandArrayService($secondTestData);
        $result = $randArrayService->randNotRepeatValues(2);
        self::assertCount(2, $result);
    }
}
