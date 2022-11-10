<?php
namespace TRegx\CleanRegex\Internal\Offset;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class ByteOffset
{
    /** @var int */
    private $bytes;

    public function __construct(int $bytes)
    {
        if ($bytes < 0) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        $this->bytes = $bytes;
    }

    public function characters(string $subject): int
    {
        if (\strLen($subject) < $this->bytes) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        return \mb_strLen(\subStr($subject, 0, $this->bytes), 'UTF-8');
    }

    public function bytes(): int
    {
        return $this->bytes;
    }
}
