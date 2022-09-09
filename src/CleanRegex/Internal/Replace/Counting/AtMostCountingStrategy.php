<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

class AtMostCountingStrategy implements CountingStrategy
{
    /** @var Exceed */
    private $exeed;
    /** @var int */
    private $maximum;

    public function __construct(Definition $definition, Subject $subject, int $maximum)
    {
        $this->exeed = new Exceed($definition, $subject);
        $this->maximum = $maximum;
    }

    public function applyReplaced(int $replaced): void
    {
        if ($this->exeed->exeeds($this->maximum)) {
            throw ReplacementExpectationFailedException::superfluous($this->maximum, 'at most');
        }
    }
}
