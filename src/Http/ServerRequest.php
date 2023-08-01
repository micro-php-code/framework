<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest implements ServerRequestInterface
{
    use MessageTrait, InputTrait;

    private ServerRequestInterface $bind;

    public function __construct(string $method, $uri, array $headers = [], $body = null, string $version = '1.1', array $serverParams = [])
    {
        $this->bind = new \Nyholm\Psr7\ServerRequest($method, $uri, $headers, $body, $version, $serverParams);
    }

    public static function fromPsr7(ServerRequestInterface $request): static
    {
        return new static($request->getMethod(), $request->getUri(), $request->getHeaders(), $request->getBody(), $request->getProtocolVersion(), $request->getServerParams());
    }

    public function getServerParams(): array
    {
        return $this->bind->getServerParams();
    }

    public function getCookieParams(): array
    {
        return $this->bind->getCookieParams();
    }

    public function withCookieParams(array $cookies): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withCookieParams($cookies);
        return $new;
    }

    public function getQueryParams(): array
    {
        return $this->bind->getQueryParams();
    }

    public function withQueryParams(array $query): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withQueryParams($query);
        return $new;
    }

    public function getUploadedFiles(): array
    {
        return $this->bind->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withUploadedFiles($uploadedFiles);
        return $new;
    }

    public function getParsedBody(): object|array|null
    {
        return $this->bind->getParsedBody();
    }

    public function withParsedBody($data): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withParsedBody($data);
        return $new;
    }

    public function getAttributes(): array
    {
        return $this->bind->getAttributes();
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->bind->getAttribute($name, $default);
    }

    public function withAttribute(string $name, $value): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withAttribute($name, $value);
        return $new;
    }

    public function withoutAttribute(string $name): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withoutAttribute($name);
        return $new;
    }

    public function getRequestTarget(): string
    {
        return $this->bind->getRequestTarget();
    }

    public function withRequestTarget(string $requestTarget): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withRequestTarget($requestTarget);
        return $new;
    }

    public function getMethod(): string
    {
        return $this->bind->getMethod();
    }

    public function withMethod(string $method): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withMethod($method);
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->bind->getUri();
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withUri($uri, $preserveHost);
        return $new;
    }

    public function getProtocolVersion(): string
    {
        return $this->bind->getProtocolVersion();
    }
}