<?php

namespace Hejunjie\Tools\Cache\Interfaces;

interface DataSourceInterface
{
    /**
     * 回源读取数据
     * 
     * @param string $key 数据键
     * 
     * @return string|null 返回字符串或null
     */
    public function get(string $key): ?string;

    /**
     * 持久化数据
     * 
     * @param string $key 数据键
     * @param string $value 数据值
     * 
     * @return bool 是否成功
     */
    public function set(string $key, string $value): bool;
}
