<?php

namespace Hejunjie\Tools;

/**
 * 网络处理类
 * @package Hejunjie\Tools
 */
class HttpClient
{
    /**
     * 使用 cURL 发送 GET 请求
     * 
     * @param string $url URL地址
     * @param array $headers header数组
     * @param int $timeout 超时时间（秒）
     * 
     * @return array ['httpStatus' => 'Http Status 状态码', 'data' => '返回内容']
     * @throws Exception 如果请求失败抛出异常
     */
    public static function sendGetRequest(string $url, array $headers = [], int $timeout = 10): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('无效的 URL: ' . $url);
        }
        $ch = curl_init();
        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 设置请求头
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);
        // 获取 HTTP 状态码
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // 检查 cURL 是否发生错误
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);
            throw new \Exception('cURL 错误: ' . $errorMsg);
        }
        curl_close($ch);
        // 返回结构化结果
        return [
            'httpStatus' => $httpStatus,
            'data' => $response
        ];
    }

    /**
     * 使用 cURL 发送 POST 请求
     * 
     * @param string $url URL地址
     * @param array $headers header数组
     * @param mixed $data 请求数据
     * @param int $timeout 超时时间（秒）
     * 
     * @return array ['httpStatus' => 'Http Status 状态码', 'data' => '返回内容']
     * @throws Exception 如果请求失败抛出异常
     */
    public static function sendPostRequest(string $url, array $headers = [], mixed $data = null, int $timeout = 10): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('无效的 URL: ' . $url);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 设置请求头
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // 设置请求数据
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); // 获取 HTTP 状态码
        // 检查 cURL 是否发生错误
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);
            throw new \Exception('cURL 错误: ' . $errorMsg);
        }
        curl_close($ch);
        // 返回结构化结果
        return [
            'httpStatus' => $httpStatus,
            'data' => $response
        ];
    }
}
