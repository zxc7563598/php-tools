<?php

namespace Hejunjie\Tools;

use Exception;

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
     * @param string $dir 文件或文件夹路径
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

    /**
     * 获取文件中的唯一行（去重）
     * 
     * 可选择保存到指定的输出文件，内容较大时建议输出到指定文件
     * 
     * @param string $inputFile 指定检查的文件路径
     * @param string $outputFile 输出文件路径，如果为空字符串，则不保存结果
     * 
     * @return bool|array 
     * @throws Exception
     */
    public static function writeUniqueLinesToFile(string $inputFile, string $outputFile = ''): bool|array
    {
        ini_set('memory_limit', '-1');
        $handle = null;
        $outputHandle = null;
        $hashSet = [];
        try {
            $handle = fopen($inputFile, 'r');
            if (!$handle) {
                throw new Exception("打开输入文件失败： 输入文件：$inputFile. 请检查文件是否存在且可读。");
            }
            if (!empty($outputFile)) {
                $directory = dirname($outputFile);
                // 检查目录是否存在，不存在则创建
                if (!is_dir($directory)) {
                    if (!mkdir($directory, 0755, true)) {  // 尝试递归创建目录
                        throw new \Exception("无法创建目标目录: " . $directory);
                    }
                }
                $outputHandle = fopen($outputFile, 'w');
                if (!$outputHandle) {
                    throw new Exception("未能打开输出文件： $outputFile. 请检查目录是否可写。");
                }
            }
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if ($line !== '' && !isset($hashSet[$line])) {
                    $hashSet[$line] = true; // 记录唯一值
                    if (!empty($outputFile)) {
                        fwrite($outputHandle, $line . PHP_EOL); // 写入输出文件
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        } finally {
            if ($handle) {
                fclose($handle);
            }
            if ($outputHandle) {
                fclose($outputHandle);
            }
        }
        // 返回结果
        if (empty($outputFile)) {
            return array_keys($hashSet); // 返回唯一值的数组
        } else {
            return true;
        }
    }

    /**
     * 从多个文件中获取交集行
     * 
     * 可选择保存到指定的输出文件，内容较大时建议输出到指定文件
     *
     * @param array $filePaths 文件路径数组
     * @param string|null $outputFile 输出文件路径，如果为空字符串，则不保存结果
     * 
     * @return bool|array
     * @throws Exception
     */
    public static function getCommonLinesFromFiles(array $filePaths, string $outputFile = ''): bool|array
    {
        ini_set('memory_limit', '-1');
        $commonLines = null;
        foreach ($filePaths as $filePath) {
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                throw new Exception("打开 $filePath 失败");
            }
            $fileLines = [];
            try {
                while (($line = fgets($handle)) !== false) {
                    $line = trim($line);
                    if (!empty($line)) {
                        $fileLines[$line] = true;
                    }
                }
            } finally {
                fclose($handle);
            }
            if (is_null($commonLines)) {
                $commonLines = $fileLines; // 初始化交集
            } else {
                $commonLines = array_intersect_key($commonLines, $fileLines); // 求交集
            }
        }
        $result = array_keys($commonLines);
        if (!empty($outputFile)) {
            $tempFile = $outputFile . '.tmp';
            file_put_contents($tempFile, implode(PHP_EOL, $result));
            rename($tempFile, $outputFile); // 使用原子写入方式
            return true;
        } else {
            return $result;
        }
    }

    /**
     * 从多个csv文件中快速提取列
     * 
     * 适用于提取一个文件夹中所有csv文件的特定列，可选择保存到指定的输出文件，内容较大时建议输出到指定文件
     * 
     * @param string $inputFolder 文件夹路径
     * @param int $columnIndex 获取第几列数据，从0开始
     * @param string $outputFile 输出文件路径，如果为空字符串，则不保存结果
     * @param bool $skipHeader 是否跳过第一行标题行
     * 
     * @return bool|array
     * @throws Exception
     */
    public static function extractColumnFromCsvFiles(string $inputFolder, int $columnIndex, string $outputFile = '', bool $skipHeader = true): bool|array
    {
        $hashSet = [];
        try {
            // 检查输入文件夹是否存在
            if (!is_dir($inputFolder)) {
                throw new Exception("输入文件夹不存在： $inputFolder");
            }
            // 打开输出文件（以追加模式写入）
            if (!empty($outputFile)) {
                $outputHandle = fopen($outputFile, 'a');
                if (!$outputHandle) {
                    throw new Exception("打开输出文件失败： $outputFile");
                }
            }
            // 获取文件夹中的所有 CSV 文件
            $csvFiles = glob("$inputFolder/*.csv");
            if (empty($csvFiles)) {
                throw new Exception("在文件夹：$inputFolder 中未发现 CSV 文件");
            }
            // 逐个处理每个 CSV 文件
            foreach ($csvFiles as $file) {
                // 打开 CSV 文件
                $handle = fopen($file, 'r');
                if (!$handle) {
                    echo "Failed to open file: $file\n";
                    continue;
                }
                $lineNumber = 0;
                while (($row = fgetcsv($handle)) !== false) {
                    $lineNumber++;
                    // 跳过第一行（标题行）
                    if ($lineNumber == 1 && $skipHeader) {
                        continue;
                    }
                    // 提取指定列的数据
                    $columnData = $row[$columnIndex] ?? '';
                    // 如果数据存在且非空，写入到输出文件
                    if (!empty($columnData)) {
                        if (!empty($outputFile)) {
                            fwrite($outputHandle, $columnData . PHP_EOL); // 写入输出文件
                        } else {
                            $hashSet[] = $columnData;
                        }
                    }
                }
                fclose($handle);
            }
            if (!empty($outputFile)) {
                fclose($outputHandle);
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
        // 返回数据
        if (!empty($outputFile)) {
            return true;
        } else {
            return $hashSet;
        }
    }
}
