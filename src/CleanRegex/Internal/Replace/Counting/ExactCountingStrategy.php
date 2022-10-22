<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;

class ExactCountingStrategy implements CountingStrategy
{
    /** @var Exceed */
    private $exceed;
    /** @var int */
    private $amount;

    public function __construct(Definition $definition, Subject $subject, int $amount)
    {
        $this->exceed = new Exceed($definition, $subject);
        $this->amount = $amount;
    }

    public function applyReplaced(int $replaced, GroupAware $groupAware): void
    {
        if ($replaced < $this->amount) {
            throw ReplacementExpectationFailedException::insufficient($replaced, $this->amount, 'exactly');
        }
        if ($this->exceed->exeeds($this->amount)) {
            throw ReplacementExpectationFailedException::superfluous($this->amount, 'exactly');
        }
    }
}
