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
            throw ReplacementExpectationFailedException::superfluous($this->maximum, 'at most');
        }
    }
}
