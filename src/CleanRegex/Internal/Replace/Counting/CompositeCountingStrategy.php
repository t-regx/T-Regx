<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

class CompositeCountingStrategy implements CountingStrategy
{
    /** @var CountingStrategy */
    private $strategy1;
    /** @var CountingStrategy */
    private $strategy2;

    public function __construct(CountingStrategy $strategy1, CountingStrategy $strategy2)
    {
        $this->strategy1 = $strategy1;
        $this->strategy2 = $strategy2;
    }

    public function count(int $replaced): void
    {
        $this->strategy1->count($replaced);
        $this->strategy2->count($replaced);
    }
}
