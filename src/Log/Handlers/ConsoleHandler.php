<?php

namespace Hejunjie\Tools\Log\Handlers;

use Hejunjie\Tools\Log\LogHandlerInterface;

/**
 * 控制台日志处理器
 * 
 * @package Hejunjie\Tools\Log\Handlers
 */
class ConsoleHandler implements LogHandlerInterface
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
    public function handle(string $level, string $title, string $message, array $context = []): void
    {
        echo sprintf("[%s] %s: %s-%s %s\n", date('Y-m-d H:i:s'), $level, $title, $message, json_encode($context));
    }
}
