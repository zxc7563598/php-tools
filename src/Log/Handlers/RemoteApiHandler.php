<?php

namespace Hejunjie\Tools\Log\Handlers;

use Hejunjie\Tools\HttpClient;
use Hejunjie\Tools\Log\LogFormatterInterface;
use Hejunjie\Tools\Log\LogHandlerInterface;
use Hejunjie\Tools\Log\Formatters\JsonFormatter;

/**
 * 远程接口日志处理器
 * 
 * @package Hejunjie\Tools\Log\Handlers
 */
class RemoteApiHandler implements LogHandlerInterface
{
    private string $endpoint;
    private LogFormatterInterface $formatter;

    /**
     * 构造函数
     * 
     * @param string $endpoint 远程接口地址
     * @param LogFormatterInterface $formatter 格式化器
     * 
     * @return void 
     */
    public function __construct(string $endpoint, LogFormatterInterface $formatter = new JsonFormatter())
    {
        $this->endpoint = $endpoint;
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
        $payload = $this->formatter->format($level, $message, $context);
        HttpClient::sendPostRequest($this->endpoint, ['Content-Type: application/json'], $payload);
    }
}
