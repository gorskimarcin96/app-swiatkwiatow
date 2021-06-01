<?php


namespace App\Utils;


use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class RandArrayService implements RandArrayServiceInterface
{
    private const CACHE_PREFIX = 'rand_service';
    private ?string $cacheKeyData;
    private array $data;
    private array $newRandData = [];
    private FilesystemAdapter $filesystemAdapter;

    /**
     * RandArrayService constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->filesystemAdapter = new FilesystemAdapter();
        $this->data = $data;
        $this->generateCacheKey();
    }

    /**
     * @param int $resultNumber
     * @return array
     * @throws InvalidArgumentException
     */
    public function randNotRepeatValues(int $resultNumber = 1): array
    {
        $this->randData($resultNumber);
        $this->setNewRandDataToCacheItem();

        if($this->checkIsAllRanded()){
            $this->filesystemAdapter->delete($this->cacheKeyData);

            return $this->randNotRepeatValues($resultNumber);
        }

        return $this->newRandData;
    }

    private function generateCacheKey(): void
    {
        $this->cacheKeyData = self::CACHE_PREFIX . '.' . hash('md5', implode($this->data));
    }

    /**
     * @param int $randNumber
     * @throws InvalidArgumentException
     */
    private function randData(int $randNumber): void
    {
        $dataToRand = array_diff($this->data, $this->filesystemAdapter->getItem($this->cacheKeyData)->get() ?? []);
        shuffle($dataToRand);

        $this->newRandData = array_slice($dataToRand, 0, $randNumber);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setNewRandDataToCacheItem(): void
    {
        $cacheItem = $this->filesystemAdapter->getItem($this->cacheKeyData);

        $oldData = $cacheItem->get() ?? [];
        $dataToSave = array_merge($oldData, $this->newRandData);
        $cacheItem->expiresAfter(3600);
        $cacheItem->set($dataToSave);

        $this->filesystemAdapter->save($cacheItem);
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    private function checkIsAllRanded(): bool
    {
        return !$this->newRandData && $this->filesystemAdapter->getItem($this->cacheKeyData)->get();
    }
}