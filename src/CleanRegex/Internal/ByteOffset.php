<?php
namespace CleanRegex\Internal;

class ByteOffset
{
    public static function normalize(string $subject, int $offset): int
    {
        return mb_strlen(substr($subject, 0, $offset));
    }
}
