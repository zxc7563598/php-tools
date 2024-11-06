<?php

namespace Hejunjie\Tools;

/**
 * 文件处理类
 * @package Hejunjie\Tools
 */
class FileUtils
{
    /**
     * 读取文件内容
     * 
     * @param string $filePath 文件绝对路径
     * 
     * @return string
     * @throws Exception 
     */
    public static function readFile(string $filePath): string
    {
        // 检查文件是否存在且可读
        if (!file_exists($filePath)) {
            throw new \Exception("文件不存在: " . $filePath);
        }
        if (!is_readable($filePath)) {
            throw new \Exception("文件不可读: " . $filePath);
        }
        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \Exception("无法读取文件内容: " . $filePath);
        }
        return $content;
    }

    /**
     * 将内容写入文件
     * 
     * @param string $filePath 文件绝对路径
     * @param string $content 写入内容
     * 
     * @return bool
     * @throws Exception
     */
    public static function writeToFile(string $filePath, string $content): bool
    {
        $directory = dirname($filePath);
        // 检查目录是否存在，不存在则创建
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {  // 尝试递归创建目录
                throw new \Exception("无法创建目标目录: " . $directory);
            }
        }
        // 检查目录是否可写
        if (!is_writable($directory)) {
            throw new \Exception("目标目录不可写: " . $directory);
        }
        // 尝试写入文件
        if (file_put_contents($filePath, $content) === false) {
            throw new \Exception("写入文件失败: " . $filePath);
        }
        return true;
    }

    /**
     * 获取文件扩展名
     * 
     * @param string $filePath 文件绝对路径
     * 
     * @return string
     * @throws Exception
     */
    public static function getFileExtension(string $filePath): string
    {
        // 检查文件是否存在
        if (!file_exists($filePath)) {
            throw new \Exception("文件不存在: " . $filePath);
        }
        // 获取扩展名
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        // 如果没有扩展名，返回提示
        if (empty($extension)) {
            throw new \Exception("文件没有扩展名: " . $filePath);
        }
        return $extension;
    }

    /**
     * 拼接多个路径
     * 
     * @param mixed ...$paths 路径
     * 
     * @return string
     */
    public static function joinPaths(...$paths): string
    {
        // 移除每个路径的前后空格，过滤掉空字符串
        $filteredPaths = array_filter(array_map('trim', $paths), function ($path) {
            return $path !== '';
        });
        // 拼接路径，并确保不重复斜杠
        $joinedPath = preg_replace('#/+#', '/', join('/', $filteredPaths));
        // 如果第一个路径是绝对路径，保留最开始的斜杠
        if (substr($paths[0], 0, 1) === '/') {
            $joinedPath = '/' . ltrim($joinedPath, '/');
        }
        return $joinedPath;
    }

    /**
     * 获取文件名（不带扩展名）
     * 
     * @param string $filePath 文件路径
     * 
     * @return string
     * @throws Exception
     */
    public static function getFileNameWithoutExtension(string $filePath): string
    {
        // 检查文件是否存在
        if (!file_exists($filePath)) {
            throw new \Exception("文件不存在: " . $filePath);
        }
        // 返回不带扩展名的文件名
        return pathinfo($filePath, PATHINFO_FILENAME);
    }

    /**
     * 删除文件或目录
     * 
     * @param mixed $dir 文件或文件夹路径
     * 
     * @return bool 
     */
    public static function fileDelete(string $path): bool
    {
        // 如果是文件，直接删除
        if (is_file($path)) {
            return unlink($path);
        }
        // 如果是目录，递归删除
        if (!is_dir($path)) {
            return true;
        }
        if (!$dh = opendir($path)) {
            return false;
        }
        while (($file = readdir($dh)) !== false) {
            if ($file === "." || $file === "..") {
                continue;
            }
            $fullpath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
            if (is_dir($fullpath)) {
                if (!self::fileDelete($fullpath)) {
                    closedir($dh);
                    return false;
                }
            } else {
                if (!unlink($fullpath)) {
                    closedir($dh);
                    return false;
                }
            }
        }
        closedir($dh);
        return rmdir($path);
    }
}
