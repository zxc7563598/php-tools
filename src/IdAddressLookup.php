<?php

namespace Hejunjie\Tools;

/**
 * 身份证地址获取
 * @package Hejunjie\Tools
 */
class IdAddressLookup
{
    private static $instance = null;    // 单例实例
    private $addressData;               // 身份证地址数据
    private $cache = [];                // 查询缓存

    /**
     * 根据身份证号获取地址信息
     * 
     * 该方法仅保障了最低可用性，如果需要提升响应速度与效率，建议调用 getAll() 获取全部数据存储 Redis 自行处理
     * 
     * @param string $idNumber 身份证号码
     * @return array 
     */
    public static function getAddressInfo(string $idNumber): array
    {
        $instance = self::getInstance();
        // 检查缓存
        if (isset($instance->cache[$idNumber])) {
            return $instance->cache[$idNumber];
        }
        // 获取身份证前6位
        $prefix = substr($idNumber, 0, 6);
        // 获取地址信息
        $address = $instance->addressData[$prefix] ?? [
            'province' => '未知',
            'city' => '未知',
            'area' => '未知',
        ];
        // 缓存结果
        $instance->cache[$idNumber] = $address;
        return $address;
    }

    /**
     * 获取全部数据
     * 
     * @return array [...'身份证前6位' => ['province' => '省', 'city' => '市', 'area' => '区']]
     * @throws Exception 
     */
    public static function getAll(): array
    {
        $instance = self::getInstance();
        return $instance->addressData;
    }

    // 私有构造函数
    private function __construct()
    {
        // 加载身份证地址数据
        $this->addressData = json_decode(file_get_contents(__DIR__ . '/database/idcard/idcard.json'), true);
    }

    // 单例模式获取实例
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
