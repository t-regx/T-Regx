<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class ByteOffset
{
    /** @var int */
    private $bytes;

    public function __construct(int $bytes)
    {
        $this->bytes = $bytes;
    }

    public function characters(string $subject): int
    {
        if (\strlen($subject) < $this->bytes) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        return \mb_strlen(\substr($subject, 0, $this->bytes));
    }

    public function bytes(): int
    {
        return $this->bytes;
    }

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
