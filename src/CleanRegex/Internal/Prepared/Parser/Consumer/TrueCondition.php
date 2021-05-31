<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;

class TrueCondition implements Condition
{
    public function met(EntitySequence $entities): bool
    {
        return true;
    }

    public function commit(): void
    {
    }
}
