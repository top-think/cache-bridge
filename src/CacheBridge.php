<?php

namespace think\cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

class CacheBridge implements CacheItemPoolInterface
{
    protected $cache;

    /**
     * 延期保存的缓存队列
     * @var array
     */
    protected $deferred = [];

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getItem($key)
    {
        $cacheItem = new CacheItem($key);
        if ($this->cache->has($key)) {
            $cacheItem->set($this->cache->get($key));
        }
        return $cacheItem;
    }

    public function getItems(array $keys = [])
    {
        $result = [];
        foreach ($keys as $key) {
            $result[] = $this->getItem($key);
        }
        return $result;
    }

    public function hasItem($key)
    {
        return $this->cache->has($key);
    }

    public function clear()
    {
        return $this->cache->clear();
    }

    public function deleteItem($key)
    {
        return $this->cache->delete($key);
    }

    public function deleteItems(array $keys)
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }
        return true;
    }

    public function save(CacheItemInterface $item)
    {
        if ($item->getKey()) {
            return $this->cache->set($item->getKey(), $item->get(), $item->getExpire());
        }
        return false;
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

    public function commit()
    {
        foreach ($this->deferred as $key => $item) {
            $result = $this->save($item);
            unset($this->deferred[$key]);
            if (false === $result) {
                return false;
            }
        }
        return true;
    }
}
