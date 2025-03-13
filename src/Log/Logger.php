<?php

namespace Hejunjie\Tools\Log;

/**
 * 日志处理器接口
 * 
 * @package Hejunjie\Tools\Log
 */
class Logger
{
    private array $handlers = [];

    public function __construct(array $handlers = [])
    {
        $this->handlers = $handlers;
    }

    /**
     * 添加日志处理器
     * 
     * @param string $level 日志级别
     * @param string $title 日志标题
     * @param string $message 日志内容
     * @param array $context 上下文
     * 
     * @return void 
     */
    public function log(string $level, string $title, string $message, array $context = []): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($level, $title, $message, $context);
        }
    }

    /**
     * 记录调试信息
     * 
     * @param string $title 日志标题
     * @param string $message 日志内容
     * @param array $context 上下文
     * 
     * @return void 
     */
    public function info(string $title, string $message, array $context = []): void
    {
        $this->log('INFO', $title, $message, $context);
    }

    /**
     * 记录错误信息
     * 
     * @param string $title 日志标题
     * @param string $message 日志内容
     * @param array $context 上下文
     * 
     * @return void 
     */
    public function error(string $title, string $message, array $context = []): void
    {
        $this->log('ERROR', $title, $message, $context);
    }

    /**
     * 记录警告信息
     * 
     * @param string $title 日志标题
     * @param string $message 日志内容
     * @param array $context 上下文
     * 
     * @return void 
     */
    public function warning(string $title, string $message, array $context = []): void
    {
        $this->log('WARNING', $title, $message, $context);
    }
}
