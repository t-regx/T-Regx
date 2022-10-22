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
            throw ReplacementExpectationFailedException::insufficient($replaced, $this->amount, 'exactly');
        }
        if ($replaced > $this->amount) {
            throw ReplacementExpectationFailedException::superfluous($this->amount, 'exactly');
        }
    }
}
