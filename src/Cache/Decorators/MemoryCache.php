<?php

namespace Hejunjie\Tools\Cache\Decorators;

use Exception;
use Hejunjie\Tools\Cache\CacheDecorator;
use Hejunjie\Tools\Cache\Interfaces\DataSourceInterface;

class MemoryCache extends CacheDecorator
{
    private array $cache = [];
    private int $ttl;
    private int $maxItems;
    private int $hits = 0;
    private int $misses = 0;

    /**
     * 构造函数
     * 
     * @param DataSourceInterface $wrapped 
     * @param int $ttl 有效时间
     * @param int $maxItems 最大存储数量
     * 
     * @return void 
     */
    public function __construct(
        DataSourceInterface $wrapped,
        int $ttl = 3600,
        int $maxItems = 1024
    ) {
        parent::__construct($wrapped);
        $this->ttl = $ttl;
        $this->maxItems = $maxItems;
    }

    /**
     * 获取缓存
     * 
     * @param string $key key
     * 
     * @return string|null 
     * @throws Exception 
     */
    public function get(string $key): ?string
    {
        $this->validateKey($key);
        if (isset($this->cache[$key])) {
            $entry = $this->cache[$key];
            if ($entry['expire'] > time()) {
                $this->hits++;
                $entry['access'] = microtime(true);
                return $entry['value'];
            }
            unset($this->cache[$key]);
        }
        $this->misses++;
        $content = $this->wrapped->get($key);
        if ($content !== null) {
            $this->store($key, $content);
        }
        return $content;
    }

    /**
     * 设置缓存
     * 
     * @param string $key key
     * @param string $value 存储值
     * 
     * @return bool 
     * @throws Exception 
     */
    public function set(string $key, string $value): bool
    {
        $this->validateKey($key);
        $result = $this->wrapped->set($key, $value);
        if ($result) {
            $this->store($key, $value);
            $this->evictIfNeeded();
        }
        return $result;
    }

    /**
     * 存储缓存
     * 
     * @param string $key key
     * @param string $value 存储值
     * 
     * @return void 
     */
    private function store(string $key, string $value): void
    {
        $this->cache[$key] = [
            'value'  => $value,
            'expire' => time() + $this->ttl,
            'access' => microtime(true)
        ];
    }

    /**
     * 淘汰缓存
     * 
     * @return void 
     */
    private function evictIfNeeded(): void
    {
        if (count($this->cache) > $this->maxItems) {
            // 按访问时间和过期时间排序
            uasort($this->cache, function ($a, $b) {
                return [$b['access'], $b['expire']] <=> [$a['access'], $a['expire']];
            });
            array_pop($this->cache);
        }
    }

    /**
     * 获取缓存统计信息
     * 
     * @return array 
     */
    public function getStats(): array
    {
        return [
            'hits' => $this->hits,
            'misses' => $this->misses,
            'hit_rate' => $this->hits / ($this->hits + $this->misses),
            'items' => count($this->cache)
        ];
    }

    /**
     * 验证key
     * 
     * @param string $key key
     * 
     * @return void 
     * @throws Exception 
     */
    private function validateKey(string $key): void
    {
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $key)) {
            throw new \Exception("{$key} 格式无效");
        }
    }
}
