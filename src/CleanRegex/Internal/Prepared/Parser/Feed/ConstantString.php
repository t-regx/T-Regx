<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\Condition;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

class ConstantString implements Condition
{
    /** @var ShiftString */
    private $shiftString;
    /** @var string */
    private $string;

    public function __construct(ShiftString $shiftString, string $string)
    {
        $this->shiftString = $shiftString;
        $this->string = $string;
    }

    public function consumable(): bool
    {
        return $this->shiftString->startsWith($this->string);
    }

    public function met(EntitySequence $entities): bool
    {
        return $this->consumable();
    }

    public function commit(): void
    {
        $this->shiftString->shift($this->string);
    }
}
