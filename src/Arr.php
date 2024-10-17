<?php

namespace Hejunjie\Tools;

/**
 * 数组处理类
 * @package Hejunjie\Tools
 */
class Arr
{
    /**
     * 获取两个数组的交集
     * 
     * @param array $array1 数组1
     * @param array $array2 数组2
     * 
     * @return array 
     * @throws Exception
     */
    public static function arrayIntersect(array $array1, array $array2): array
    {
        // 检查输入的两个参数是否都是数组
        if (!is_array($array1) || !is_array($array2)) {
            throw new \Exception('输入的参数必须是数组');
        }
        return array_intersect($array1, $array2);
    }

    /**
     * 根据二维数组中的指定字段排序
     * 
     * @param array $array 二维数组
     * @param string $field 排序字段
     * @param bool $ascending 是否按升序排序（默认升序）
     * 
     * @return array
     * @throws Exception
     */
    public static function sortByField(array $array, string $field, bool $ascending = true): array
    {
        // 检查数组中的每个元素是否为数组，并且包含指定的字段
        foreach ($array as $item) {
            if (!is_array($item)) {
                throw new \Exception('输入的数组中的每个元素必须是关联数组');
            }
            if (!array_key_exists($field, $item)) {
                throw new \Exception("字段 '$field' 不存在于数组中的某些元素中");
            }
        }
        // 使用 usort 对数组进行排序
        usort($array, function ($a, $b) use ($field, $ascending) {
            return $ascending ? $a[$field] <=> $b[$field] : $b[$field] <=> $a[$field];
        });
        return $array;
    }

    /**
     * 根据二维数组中指定字段去重
     * 
     * @param array $array 二维数组
     * @param string $field 指定的去重字段
     * 
     * @return array
     * @throws Exception
     */
    public static function removeDuplicatesByField(array $array, string $field): array
    {
        // 用于记录字段值的数组
        $unique = [];
        // 存储去重后的结果数组
        $result = [];
        // 遍历数组，检查并去重
        foreach ($array as $item) {
            // 确保每个项目是数组并且包含指定的字段
            if (!is_array($item)) {
                throw new \Exception('输入的数组中的每个元素必须是关联数组');
            }
            if (!array_key_exists($field, $item)) {
                throw new \Exception("字段 '$field' 不存在于数组中的某些元素中");
            }
            // 如果该字段值不在unique数组中，则添加到结果中
            if (!in_array($item[$field], $unique, true)) {
                $unique[] = $item[$field];
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * 根据二维数组中的指定字段进行分组
     * 
     * @param array $array 二维数组
     * @param string $field 指定的分组字段
     * 
     * @return array
     * @throws Exception
     */
    public static function groupByField(array $array, string $field): array
    {
        // 初始化分组数组
        $grouped = [];
        // 遍历数组并根据字段进行分组
        foreach ($array as $item) {
            // 确保每个项目是数组并且包含指定的字段
            if (!is_array($item)) {
                throw new \Exception('输入的数组中的每个元素必须是关联数组');
            }
            if (!array_key_exists($field, $item)) {
                throw new \Exception("字段 '$field' 不存在于数组中的某些元素中");
            }
            // 按字段值进行分组
            $grouped[$item[$field]][] = $item;
        }
        return $grouped;
    }

    /**
     * 数组转换为 CSV 格式的字符串
     * 
     * @param array $array 数组
     * @param string $delimiter CSV 分隔符，默认为逗号
     * @param string $enclosure CSV 包裹符号，默认为双引号
     * @param string $escapeChar 转义符号，默认为反斜杠
     * 
     * @return string
     */
    public static function arrayToCsv(array $array, string $delimiter = ',', string $enclosure = '"', string $escapeChar = '\\'): string
    {
        // 开启输出缓冲区
        ob_start();
        // 打开 PHP 输出流
        $output = fopen('php://output', 'w');
        // 检查文件句柄是否打开成功
        if ($output === false) {
            throw new \Exception('无法打开输出流');
        }
        // 遍历数组并写入 CSV 格式
        foreach ($array as $row) {
            if (!is_array($row)) {
                throw new \Exception('输入的每一行都必须是一个数组');
            }
            fputcsv($output, $row, $delimiter, $enclosure, $escapeChar);
        }
        // 关闭文件句柄
        fclose($output);
        // 获取缓冲区内容
        return ob_get_clean();
    }
}
