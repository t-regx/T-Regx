<?php
namespace Regex\Internal;

use Regex\UnicodeException;

class UnicodeString
{
    private string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function offset(int $byteOffset): int
    {
        $leading = \subStr($this->string, 0, $byteOffset);
        if (\mb_check_encoding($leading, 'UTF-8')) {
            return \mb_strLen($leading, 'UTF-8');
        }
        throw new UnicodeException("Byte offset $byteOffset does not point to a valid unicode code point.");
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
