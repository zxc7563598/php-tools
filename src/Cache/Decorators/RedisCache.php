<?php

namespace Hejunjie\Tools\Cache\Decorators;

use Exception;
use Hejunjie\Tools\Cache\CacheDecorator;
use Hejunjie\Tools\Cache\Interfaces\DataSourceInterface;
use Redis;
use RedisCluster;

class RedisCache extends CacheDecorator
{
    private $redis;
    private string $prefix;
    private array $config;
    private bool $persistent;

    /**
     * 构造函数
     * 
     * @param DataSourceInterface $wrapped 
     * @param array $config redis配置
     * @param string $prefix 前缀
     * @param bool $persistent 持久化链接 
     * 
     * @return void 
     */
    public function __construct(
        DataSourceInterface $wrapped,
        array $config,
        string $prefix = 'cache:',
        bool $persistent = true
    ) {
        parent::__construct($wrapped);
        $this->config = $config;
        $this->prefix = $prefix;
        $this->persistent = $persistent;
        $this->initRedis();
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
        try {
            $value = $this->redis->get($key);
            if ($value !== false) {
                return $value;
            }
            $content = $this->wrapped->get($key);
            if ($content !== null) {
                $this->set($key, $content);
            }
            return $content;
        } catch (\Exception $e) {
            throw new \Exception("Redis error: " . $e->getMessage());
        }
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
        $ttl = $this->getTTL($key);
        try {
            $result = $this->wrapped->set($key, $value);
            if ($result) {
                $this->redis->setex($key, $ttl, $value);
            }
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("redis异常: " . $e->getMessage());
        }
    }

    /**
     * 初始化Redis
     * 
     * @return void 
     */
    private function initRedis(): void
    {
        if ($this->config['cluster'] ?? false) {
            $this->redis = new RedisCluster(
                null,
                $this->config['hosts'],
                $this->config['timeout'] ?? 2.0,
                $this->config['read_timeout'] ?? 2.0,
                $this->persistent,
                $this->config['password'] ?? null
            );
        } else {
            $this->redis = new Redis();
            $connectMethod = $this->persistent ? 'pconnect' : 'connect';
            $this->redis->{$connectMethod}(
                $this->config['host'],
                $this->config['port'] ?? 6379,
                $this->config['timeout'] ?? 2.0
            );
            if (isset($this->config['password'])) {
                $this->redis->auth($this->config['password']);
            }
            if (isset($this->config['db'])) {
                $this->redis->select($this->config['db']);
            }
        }
        $this->redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
        $this->redis->setOption(Redis::OPT_PREFIX, $this->prefix);
    }

    /**
     * 获取TTL
     * 
     * @return int 
     */
    private function getTTL(): int
    {
        $baseTTL = $this->config['ttl'] ?? 3600;
        return $baseTTL;
        // return $baseTTL + mt_rand(0, 300); // 防雪崩
    }

    /**
     * 验证key
     * 
     * @param string $key key
     * 
     * @return string 
     * @throws Exception 
     */
    private function validateKey(string $key): void
    {
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $key)) {
            throw new \Exception("{$key} 格式无效");
        }
    }

    /**
     * 析构函数
     * 
     * @return void 
     */
    public function __destruct()
    {
        if (!$this->persistent && $this->redis instanceof Redis) {
            $this->redis->close();
        }
    }
}
