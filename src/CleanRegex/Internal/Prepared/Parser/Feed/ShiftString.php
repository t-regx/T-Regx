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
            throw new InternalCleanRegexException();
        }
        $this->offset += \mb_strlen($string);
    }

    public function startsWith(string $infix): bool
    {
        return \mb_substr($this->string, $this->offset, \mb_strlen($infix)) === $infix;
    }

    public function empty(): bool
    {
        return $this->offset >= \mb_strlen($this->string);
    }

    public function firstLetter(): string
    {
        if ($this->offset >= \mb_strlen($this->string)) {
            throw new InternalCleanRegexException();
        }
        return \mb_substr($this->string, $this->offset, 1);
    }

    public function content(): string
    {
        return \mb_substr($this->string, $this->offset);
    }
}
