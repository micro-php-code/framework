<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use Psr\Http\Message\StreamInterface;

trait MessageTrait
{
    public function withProtocolVersion(string $version): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withProtocolVersion($version);
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->bind->getHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return $this->bind->hasHeader($name);
    }

    public function getHeader(string $name): array
    {
        return $this->bind->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->bind->getHeaderLine($name);
    }

    public function withHeader(string $name, $value): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withHeader($name, $value);
        return $new;
    }

    public function withAddedHeader(string $name, $value): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withAddedHeader($name, $value);
        return $new;
    }

    public function withoutHeader(string $name): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withoutHeader($name);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->bind->getBody();
    }

    public function withBody(StreamInterface $body): static
    {
        $new = clone $this;
        $new->bind = $this->bind->withBody($body);
        return $new;
    }
}