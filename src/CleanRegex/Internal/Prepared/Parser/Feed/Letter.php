<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\Condition;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

class Letter implements Condition
{
    /** @var ShiftString */
    private $shiftString;

    public function __construct(ShiftString $shiftString)
    {
        $this->shiftString = $shiftString;
    }

    public function consumable(): bool
    {
        return !$this->shiftString->empty();
    }

    public function consume(): string
    {
        $letter = $this->shiftString->firstLetter();
        $this->shiftString->shift($letter);
        return $letter;
    }

    public function met(EntitySequence $entities): bool
    {
        return $this->consumable();
    }

    public function commit(): void
    {
        $this->consume();
    }
}
