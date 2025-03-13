<?php

namespace Hejunjie\Tools\Log;

/**
 * 日志格式化器接口
 * 
 * @package Hejunjie\Tools\Log
 */
interface LogFormatterInterface
{
    public function format(string $level, string $message, array $context = []): string;
}
