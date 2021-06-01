<?php


namespace App\Utils;


interface RandArrayServiceInterface
{
    public function randNotRepeatValues(int $resultNumber = 1): array;
}