<?php
namespace Regex;

final class Detail
{
    private string $text;
    private int $offset;

    public function __construct(string $text, int $offset)
    {
        $this->text = $text;
        $this->offset = $offset;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function byteOffset(): int
    {
        return $this->offset;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
