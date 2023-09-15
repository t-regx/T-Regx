<?php
namespace Regex;

final class Detail
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
