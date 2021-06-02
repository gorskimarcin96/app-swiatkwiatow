<?php


namespace App\Utils\RandArrayService;


interface RandArrayServiceInterface
{
    /**
     * @param int $resultNumber
     * @return array
     */
    public function randNotRepeatValues(int $resultNumber = 1): array;
}