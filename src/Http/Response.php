<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{
    use MessageTrait;

    private ResponseInterface $bind;

    public function __construct(int $status = 200, array $headers = [], $body = null, string $version = '1.1', string $reason = null)
    {
        $this->bind = new \Nyholm\Psr7\Response($status, $headers, $body, $version, $reason);
    }

    public function getStatusCode(): int
    {
        return $this->bind->getStatusCode();
    }

    public function withStatus(int $code, string $reasonPhrase = ''): void
    {
        $this->bind->withStatus($code, $reasonPhrase);
    }

    public function getReasonPhrase(): string
    {
        return $this->bind->getReasonPhrase();
    }

    public function getProtocolVersion(): string
    {
        return $this->bind->getProtocolVersion();
    }
}
