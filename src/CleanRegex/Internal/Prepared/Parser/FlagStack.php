<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

class FlagStack
{
    /** @var SubpatternFlags */
    private $groundState;
    /** @var SubpatternFlags[] */
    private $stack = [];

    public function __construct(SubpatternFlags $groundState)
    {
        $this->groundState = $groundState;
    }

    public function put(SubpatternFlags $flags): void
    {
        $this->stack[] = $flags;
    }

    public function peek(): SubpatternFlags
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
