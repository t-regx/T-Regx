<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class ShiftString
{
    /** @var string */
    private $string;
    /** @var int */
    private $offset;

    public function __construct(string $string)
    {
        $this->string = $string;
        $this->offset = 0;
    }

    public function shift(string $string): void
    {
        if (!$this->startsWith($string)) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        $this->offset += \strLen($string);
    }

    public function startsWith(string $infix): bool
    {
        return \subStr($this->string, $this->offset, \strLen($infix)) === $infix;
    }

    public function empty(): bool
    {
        return $this->offset >= \strLen($this->string);
    }

    public function firstLetter(): string
    {
        if ($this->offset >= \strLen($this->string)) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        return \subStr($this->string, $this->offset, 1);
    }

    public function content(): string
    {
        return \subStr($this->string, $this->offset);
    }
}
