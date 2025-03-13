<?php

namespace Hejunjie\Tools\Log\Handlers;

use Hejunjie\Tools\Log\LogFormatterInterface;
use Hejunjie\Tools\Log\LogHandlerInterface;
use Hejunjie\Tools\Log\Formatters\DefaultFormatter;

/**
 * 文件日志处理器
 * 
 * @package Hejunjie\Tools\Log\Handlers
 */
class FileHandler implements LogHandlerInterface
{
    private string $logDir;   // 日志存放目录
    private int $maxFileSize; // 最大文件大小（单位：字节）
    private LogFormatterInterface $formatter;

    /**
     * 构造函数
     * 
     * @param string $logDir 日志路径
     * @param int $maxFileSize 最大文件大小
     * @param LogFormatterInterface $formatter 格式化器
     * 
     * @return void 
     */
    public function __construct(string $logDir, int $maxFileSize = 5000000, LogFormatterInterface $formatter = new DefaultFormatter())
    {
        $this->logDir = rtrim($logDir, '/') . '/';
        $this->maxFileSize = $maxFileSize;
        $this->formatter = $formatter;
    }

    /**
     * 日志处理
     * 
     * @param string $level 日志级别
     * @param string $title 日志标题
     * @param string $message 日志内容
     * @param array $context 上下文
     * 
     * @return void 
     */
    public function handle(string $level, string $title, string $message, array $context = []): void
    {
        $logFile = $this->getLogFile($title);
        $formattedMessage = $this->formatter->format($level, $message, $context);

        // 检查文件大小，进行文件分割
        if (file_exists($logFile) && filesize($logFile) > $this->maxFileSize) {
            $this->rotateLogFiles($logFile);
        }

        file_put_contents($logFile, $formattedMessage . PHP_EOL, FILE_APPEND);
    }

    /**
     * 获取日志文件
     * 
     * @param string $title 文件名
     * 
     * @return string 
     */
    private function getLogFile(string $title): string
    {
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
        return "{$this->logDir}{$title}.log";
    }

    /**
     * 滚动日志文件（log_1.log, log_2.log ...）
     * 
     * @param string $logFile 日志文件
     * 
     * @return void 
     */
    private function rotateLogFiles(string $logFile): void
    {
        $index = 1;
        while (file_exists("{$logFile}.{$index}")) {
            $index++;
        }
        rename($logFile, "{$logFile}.{$index}");
    }
}
