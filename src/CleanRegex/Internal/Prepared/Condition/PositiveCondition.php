<?php
namespace TRegx\CleanRegex\Internal\Prepared\Condition;

class PositiveCondition implements Condition
{
    public function suitable(string $candidate): bool
    {
        return true;
    }
}
