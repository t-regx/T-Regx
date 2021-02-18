<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class ByteOffset
{
    public static function toCharacterOffset(string $subject, int $offset): int
    {
        if (\strlen($subject) < $offset) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        return \mb_strlen(\substr($subject, 0, $offset));
    }
}
