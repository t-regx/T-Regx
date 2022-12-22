<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Condition;

class CompositeCondition implements Condition
{
    /** @var Condition */
    private $condition1;
    /** @var Condition */
    private $condition2;

    public function __construct(Condition $condition1, Condition $condition2)
    {
        $this->condition1 = $condition1;
        $this->condition2 = $condition2;
    }

    public function suitable(string $candidate): bool
    {
        return $this->condition1->suitable($candidate) && $this->condition2->suitable($candidate);
    }
}
