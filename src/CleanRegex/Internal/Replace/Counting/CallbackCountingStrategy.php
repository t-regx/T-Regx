<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

class CallbackCountingStrategy implements CountingStrategy
{
    /** @var callback */
    private $countReceiver;

    public function __construct(callable $countReceiver)
    {
        $this->countReceiver = $countReceiver;
    }

    public function count(int $replaced): void
    {
        $receiver = $this->countReceiver;
        $receiver($replaced);
    }
}
