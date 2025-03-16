<?php

namespace Hejunjie\Tools\Cache\Decorators;

use Exception;
use Hejunjie\Tools\Cache\Interfaces\DataSourceInterface;

class FileCache implements DataSourceInterface
{
    protected DataSourceInterface $wrapped;
    private string $cacheDir;
    private int $ttl;

    /**
     * 构造函数
     * @param DataSourceInterface $wrapped 
     * @param string $cacheDir 文件存储路径
     * @param int $ttl 有效时间
     * 
     * @return void 
     * @throws Exception 
     */
    public function __construct(
        DataSourceInterface $wrapped,
        string $cacheDir,
        int $ttl = 3600
    ) {
        $this->wrapped = $wrapped;
        $this->validateDir($cacheDir);
        $this->cacheDir = rtrim($cacheDir, '/');
        $this->ttl = $ttl;
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
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath)) {
            $data = $this->readWithLock($filePath);
            if ($data['expire'] > time()) {
                echo '[文件]获取成功' . PHP_EOL;
                return $data['content'];
            }
            $this->deleteFile($filePath);
        }
        echo '[文件]获取失败' . PHP_EOL;
        $content = $this->wrapped->get($key);
        if ($content !== null) {
            $this->storeToFile($key, $content);
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
        $result = $this->wrapped->set($key, $value);
        if ($result) {
            $this->storeToFile($key, $value);
        }
        return $result;
    }

    /**
     * 删除缓存
     * 
     * @param string $key key
     * @param string $value 存储值
     * 
     * @return void 
     * @throws Exception 
     */
    private function storeToFile(string $key, string $value): void
    {
        $filePath = $this->getFilePath($key);
        $data = [
            'expire'  => time() + $this->ttl,
            'content' => $value
        ];
        $tempFile = tempnam($this->cacheDir, 'tmp');
        if (file_put_contents($tempFile, serialize($data)) === false) {
            throw new \Exception("写入缓存文件失败");
        }
        // 原子操作替换文件
        if (!rename($tempFile, $filePath)) {
            unlink($tempFile);
            throw new \Exception("重命名缓存文件失败");
        }
    }

    /**
     * 读取缓存文件
     * 
     * @param string $path 文件路径
     * 
     * @return array 
     * @throws Exception 
     */
    private function readWithLock(string $path): array
    {
        $fp = fopen($path, 'r');
        if (!flock($fp, LOCK_SH | LOCK_NB, $wouldBlock)) {
            if ($wouldBlock) {
                throw new \Exception("文件锁定超时");
            }
        }
        $data = unserialize(stream_get_contents($fp));
        flock($fp, LOCK_UN);
        fclose($fp);
        return $data;
    }

    /**
     * 获取文件路径
     * 
     * @param string $key key
     * 
     * @return string 
     */
    private function getFilePath(string $key): string
    {
        $hash = hash('sha256', $key);
        return "{$this->cacheDir}/{$hash}.cache";
    }

    /**
     * 验证目录
     * 
     * @param string $dir 目录
     * 
     * @return void 
     * @throws Exception 
     */
    private function validateDir(string $dir): void
    {
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new \Exception("无法创建缓存目录: {$dir}");
        }
        if (!is_writable($dir)) {
            throw new \Exception("缓存目录不可写: {$dir}");
        }
    }

    /**
     * 删除文件
     * 
     * @param string $path 文件路径
     * 
     * @return void 
     */
    private function deleteFile(string $path): void
    {
        @unlink($path);
    }
}
