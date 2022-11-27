<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

class AtMostCountingStrategy implements CountingStrategy
{
    /** @var int */
    private $maximum;

    public function __construct(int $maximum)
    {
        $this->maximum = $maximum;
    }

    public function applyReplaced(int $replaced): void
    {
        if ($replaced > $this->maximum) {
            throw new ReplacementExpectationFailedException("Expected to perform at most $this->maximum replacement(s), " .
                "but more than $this->maximum replacement(s) would have been performed");
        }
    }
}
