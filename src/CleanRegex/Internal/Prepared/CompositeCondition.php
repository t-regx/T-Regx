<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Condition;

class CompositeCondition implements Condition
{
    /** @var Condition[] */
    private $conditions;

    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    public function suitable(string $candidate): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->suitable($candidate)) {
                return false;
            }
        }
        return true;
    }
}
