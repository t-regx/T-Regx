<?php
namespace Regex;

final class Detail
{
    private string $text;
    private int $offset;
    private string $subject;

    public function __construct(string $text, int $offset, string $subject)
    {
        $this->text = $text;
        $this->offset = $offset;
        $this->subject = $subject;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function offset(): int
    {
        return \mb_strLen(\subStr($this->subject, 0, $this->offset));
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
