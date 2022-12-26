<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

class Feed
{
    /** @var ShiftString */
    private $shiftString;
    /** @var Letter */
    private $letter;
    /** @var PosixClassCondition */
    private $posixClass;
    /** @var ConstantStrings */
    private $constantStrings;

    public function __construct(string $string)
    {
        $this->shiftString = new ShiftString($string);
        $this->letter = new Letter($this->shiftString);
        $this->posixClass = new PosixClassCondition($this->shiftString);
        $this->constantStrings = new ConstantStrings($this->shiftString);
    }

    public function letter(): Letter
    {
        return $this->letter;
    }

    public function stringLengthBeforeAny(string $characters): int
    {
        return $this->shiftString->stringLengthBeforeAny($characters);
    }

    public function string(string $string): ConstantString
    {
        return $this->constantStrings->string($string);
    }

    public function posixClass(): PosixClassCondition
    {
        return $this->posixClass;
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

    public function subString(int $length): string
    {
        return $this->shiftString->subString($length);
    }
}
