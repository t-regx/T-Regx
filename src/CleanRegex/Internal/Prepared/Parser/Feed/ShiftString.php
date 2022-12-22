<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class ShiftString
{
    /** @var string */
    private $string;
    /** @var int */
    private $stringLength;
    /** @var int */
    private $offset;

    public function __construct(string $string)
    {
        $this->string = $string;
        $this->stringLength = \strLen($string);
        $this->offset = 0;
    }

    public function shift(string $string): void
    {
        $this->offset += \strLen($string);
    }

    public function startsWith(string $infix): bool
    {
        return \subStr($this->string, $this->offset, \strLen($infix)) === $infix;
    }

    public function empty(): bool
    {
        return $this->offset >= $this->stringLength;
    }

    public function firstLetter(): string
    {
        return \subStr($this->string, $this->offset, 1);
    }

    public function content(): string
    {
        return \subStr($this->string, $this->offset);
    }
}
