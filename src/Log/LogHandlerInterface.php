<?php

namespace Hejunjie\Tools\Log;

/**
 * 日志处理器接口
 * 
 * @package Hejunjie\Tools\Log
 */
interface LogHandlerInterface
{
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
    public function handle(string $level, string $title, string $message, array $context = []): void;
}
