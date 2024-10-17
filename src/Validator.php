<?php

namespace Hejunjie\Tools;

/**
 * 验证处理类
 * @package Hejunjie\Tools
 */
class Validator
{
    /**
     * 验证邮箱格式
     * 
     * @param string $email 邮箱
     * 
     * @return bool 
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * 验证电话号码格式
     * 
     * @param string $phone 手机号
     * 
     * @return bool 
     */
    public static function validatePhoneNumber(string $phone): bool
    {
        return preg_match('/^1[3-9]\d{9}$/', $phone) === 1;
    }

    /**
     * 验证 URL 格式
     * 
     * @param string $url URL
     * 
     * @return bool 
     */
    public static function validateUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
