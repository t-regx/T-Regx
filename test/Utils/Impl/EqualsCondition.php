<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Condition;

class EqualsCondition implements Condition
{
    /** @var string */
    private $delimiter;

    public function __construct(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function suitable(string $candidate): bool
    {
        return $this->delimiter === $candidate;
    }
}
