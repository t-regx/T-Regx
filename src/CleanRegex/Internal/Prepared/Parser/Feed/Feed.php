<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class Feed
{
    /** @var ShiftString */
    private $shiftString;
    /** @var ConstantStrings */
    private $constantStrings;

    public function __construct(string $string)
    {
        $this->shiftString = new ShiftString($string);
        $this->constantStrings = new ConstantStrings($this->shiftString);
    }

    public function stringLengthBeforeAny(string $characters): int
    {
        return $this->shiftString->stringLengthBeforeAny($characters);
    }

    public function string(string $string): ConstantString
    {
        return $this->constantStrings->string($string);
    }

    public function empty(): bool
    {
        return $this->shiftString->empty();
    }

    public function startsWith(string $infix): bool
    {
        return $this->shiftString->startsWith($infix);
    }

    public function commit(string $string): void
    {
        $this->shiftString->shift($string);
    }

    public function content(): string
    {
        return $this->shiftString->content();
    }

    public function firstLetter(): string
    {
        return $this->shiftString->firstLetter();
    }

    public function subString(int $length): string
    {
        return $this->shiftString->subString($length);
    }

    public function shiftSingle(): void
    {
        $this->shiftString->shiftSingle();
    }
}
