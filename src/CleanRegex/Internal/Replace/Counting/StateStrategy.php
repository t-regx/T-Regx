<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Internal\Replace\Counter;

class StateStrategy implements CountingStrategy
{
    /** @var CountingStrategy */
    private $strategy;
    /** @var Counter */
    private $counter;
    /** @var int */
    private $replaced = null;

    public function __construct(CountingStrategy $strategy, Counter $counter)
    {
        $this->strategy = $strategy;
        $this->counter = $counter;
    }

    public function applyReplaced(int $replaced): void
    {
        $this->strategy->applyReplaced($replaced);
        $this->replaced = $replaced;
    }

    public function count(): int
    {
        if ($this->replaced === null) {
            $amount = $this->counter->limitedAmount();
            $this->applyReplaced($amount);
            return $amount;
        }
        return $this->replaced;
    }
}
