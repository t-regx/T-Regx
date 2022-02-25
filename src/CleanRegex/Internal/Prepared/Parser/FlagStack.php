<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Flags;

class FlagStack
{
    /** @var Flags */
    private $groundState;
    /** @var Flags[] */
    private $stack = [];

    public function __construct(Flags $groundState)
    {
        $this->groundState = $groundState;
    }

    public function put(Flags $flags): void
    {
        $this->stack[] = $flags;
    }

    public function peek(): Flags
    {
        if (empty($this->stack)) {
            return $this->groundState;
        }
        return \end($this->stack);
    }

    public function pop(): void
    {
        \array_pop($this->stack);
    }
}
