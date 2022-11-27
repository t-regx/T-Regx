<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

class ExactCountingStrategy implements CountingStrategy
{
    /** @var int */
    private $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function applyReplaced(int $replaced): void
    {
        if ($replaced < $this->amount) {
            throw new ReplacementExpectationFailedException("Expected to perform exactly $this->amount replacement(s), " .
                "but $replaced replacement(s) were actually performed");
        }
        if ($replaced > $this->amount) {
            throw new ReplacementExpectationFailedException("Expected to perform exactly $this->amount replacement(s), " .
                "but more than $this->amount replacement(s) would have been performed");
        }
    }
}
