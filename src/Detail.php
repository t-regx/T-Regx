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
        $leading = \subStr($this->subject, 0, $this->offset);
        if (\mb_check_encoding($leading, 'UTF-8')) {
            return \mb_strLen($leading, 'UTF-8');
        }
        throw new UnicodeException("Byte offset $this->offset does not point to a valid unicode code point.");
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
