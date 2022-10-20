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
    private $limit;
    /** @var string */
    private $phrase;

    public function __construct(Definition $definition, Subject $subject, int $limit, string $phrase)
    {
        $this->exceed = new Exceed($definition, $subject);
        $this->limit = $limit;
        $this->phrase = $phrase;
    }

    public function count(int $replaced, GroupAware $groupAware): void
    {
        if ($replaced < $this->limit) {
            throw ReplacementExpectationFailedException::insufficient($replaced, $this->limit, $this->phrase);
        }
        if ($this->exceed->exeeds($this->limit)) {
            throw ReplacementExpectationFailedException::superfluous($this->limit, $this->phrase);
        }
    }
}
