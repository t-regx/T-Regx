<?php
namespace TRegx\CleanRegex\Internal;

use function mb_strlen;
use function substr;

class ByteOffset
{
    public static function toCharacterOffset(string $subject, int $offset): int
    {
        return mb_strlen(substr($subject, 0, $offset));
    }
}
