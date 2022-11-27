<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

class AtLeastCountingStrategy implements CountingStrategy
{
    /** @var int */
    private $minimum;

    public function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public function applyReplaced(int $replaced): void
    {
        if ($replaced < $this->minimum) {
            throw new ReplacementExpectationFailedException("Expected to perform at least $this->minimum replacement(s), " .
                "but $replaced replacement(s) were actually performed");
        }
    }
}
