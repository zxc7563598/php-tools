<?php

namespace Hejunjie\Tools;

/**
 * 字符串处理类
 * @package Hejunjie\Tools
 */
class Str
{
    /**
     * 检查字符串中是否存在数组中的内容
     * 
     * @param string $string 需要检查的字符串
     * @param array $array 用以匹配的内容数组
     * @param bool $ignoreCase 是否忽略大小写
     * 
     * @return bool 
     */
    public static function containsAny(string $string, array $array, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $string = mb_strtolower($string);
        }
        foreach ($array as $_array) {
            if ($ignoreCase) {
                $_array = mb_strtolower($_array);
            }
            if ($_array !== '' && str_contains($string, $_array)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 生成随机字符串
     * 
     * @param int $len 生成长度
     * @param bool $string 是否加入字符串
     * @param bool $int 是否加入数字
     * 
     * @return string 
     */
    public static function generateRandomString(int $len = 8, bool $string = true, bool $int = true): string
    {
        $str = '';
        if ($string) {
            $str .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        }
        if ($int) {
            $str .= '0123456789';
        }
        if (empty($str)) {
            return ''; // 如果不添加字母和数字，返回空字符串
        }
        $noncestr = '';
        for ($i = 0; $i < $len; $i++) {
            $noncestr .= substr($str, mt_rand(0, strlen($str) - 1), 1);
        }
        return $noncestr;
    }

    /**
     * 获取随机姓氏
     * 
     * @return string 
     */
    public static function getRandomSurname(): string
    {
        $surnames = [
            '赵',
            '钱',
            '孙',
            '李',
            '周',
            '吴',
            '郑',
            '王',
            '冯',
            '陈',
            '楮',
            '卫',
            '蒋',
            '沈',
            '韩',
            '杨',
            '朱',
            '秦',
            '尤',
            '许',
            '何',
            '吕',
            '施',
            '张',
            '孔',
            '曹',
            '严',
            '华',
            '金',
            '魏',
            '陶',
            '姜',
            '戚',
            '谢',
            '邹',
            '喻',
            '柏',
            '水',
            '窦',
            '章',
            '云',
            '苏',
            '潘',
            '葛',
            '奚',
            '范',
            '彭',
            '郎',
            '鲁',
            '韦',
            '昌',
            '马',
            '苗',
            '凤',
            '花',
            '方',
            '俞',
            '任',
            '袁',
            '柳',
            '酆',
            '鲍',
            '史',
            '唐',
            '费',
            '廉',
            '岑',
            '薛',
            '雷',
            '贺',
            '倪',
            '汤',
            '滕',
            '殷',
            '罗',
            '毕',
            '郝',
            '邬',
            '安',
            '常',
            '乐',
            '于',
            '时',
            '傅',
            '皮',
            '卞',
            '齐',
            '康',
            '伍',
            '余',
            '元',
            '卜',
            '顾',
            '孟',
            '平',
            '黄',
            '和',
            '穆',
            '萧',
            '尹'
        ];
        return $surnames[array_rand($surnames)];
    }

    /**
     * 截断字符串
     * 
     * @param string $str 字符串
     * @param int $length 长度
     * @param string $suffix 截断后添加后缀
     * 
     * @return string 
     */
    public static function truncateString(string $str, int $length, string $suffix = '...'): string
    {
        return strlen($str) > $length ? substr($str, 0, $length) . $suffix : $str;
    }

    /**
     * 字符串掩码
     * 
     * @param string $str 字符串
     * @param int $start 起始位置
     * @param int $length 掩码位数
     * @param string $mask 掩码符
     * 
     * @return string 
     */
    public static function maskString(string $str, int $start, int $length, string $mask = '*'): string
    {
        return substr($str, 0, $start) . str_repeat($mask, $length) . substr($str, $start + $length);
    }

    /**
     * 移除字符串中的所有空白字符
     * 
     * @param string $str 字符串
     * 
     * @return string 
     */
    public static function removeWhitespace(string $str): string
    {
        return preg_replace('/\s+/', '', $str);
    }

    /**
     * 字符串加密
     *
     * @param string $string 加密内容
     * @param string $key 加密key（默认长度16）
     * @param string $iv 偏移量（默认长度16）
     * 
     * @return string
     * @throws Exception 
     */
    public static function stringEncrypt(string $string, string $key = 'e9c8slrkfixr2658', string $iv = 'd89f5dkc7y1sf03g'): string
    {
        // 检查密钥和偏移量长度
        if (strlen($key) !== 16) {
            throw new \Exception("密钥长度必须为16字节");
        }
        if (strlen($iv) !== 16) {
            throw new \Exception("偏移量长度必须为16字节");
        }
        // 执行加密
        $strEncrypted = openssl_encrypt($string, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
        if ($strEncrypted === false) {
            throw new \Exception("加密失败");
        }
        return urlencode(base64_encode($strEncrypted));
    }

    /**
     * 字符串解密
     *
     * @param string $encryptedString 加密内容
     * @param string $key 解密key（默认长度16）
     * @param string $iv 偏移量（默认长度16）
     * 
     * @return string
     * @throws Exception 
     */
    public static function stringDecrypt(string $encryptedString, string $key = 'e9c8slrkfixr2658', string $iv = 'd89f5dkc7y1sf03g'): string
    {
        // 检查密钥和偏移量长度
        if (strlen($key) !== 16) {
            throw new \Exception("密钥长度必须为16字节");
        }
        if (strlen($iv) !== 16) {
            throw new \Exception("偏移量长度必须为16字节");
        }
        // 解码和解密
        $decodedString = base64_decode(urldecode($encryptedString));
        $decryptedString = openssl_decrypt($decodedString, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
        if ($decryptedString === false) {
            throw new \Exception("解密失败");
        }
        return $decryptedString;
    }

    /**
     * 根据秒数转换为可读性时间
     * 
     * @param mixed $seconds 秒数
     * 
     * @return string|bool 
     */
    public static function formatDurationFromSeconds($seconds): string|bool
    {
        if (!is_numeric($seconds)) {
            return false;
        }
        $units = [
            "year" => 31556926,
            "day" => 86400,
            "hour" => 3600,
            "minute" => 60,
            "second" => 1,
        ];
        $t = [];
        foreach ($units as $unit => $unitSeconds) {
            if ($seconds >= $unitSeconds) {
                $value = floor($seconds / $unitSeconds);
                $seconds %= $unitSeconds;
                $t[] = $value . ($value > 1 ? $unit . "s" : $unit); // 处理复数形式
            }
        }
        // 如果没有任何时间单位被添加，返回 "0秒"
        return !empty($t) ? implode(" ", $t) : "0秒";
    }
}
