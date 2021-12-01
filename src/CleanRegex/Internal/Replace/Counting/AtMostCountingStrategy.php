<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class AtMostCountingStrategy implements CountingStrategy
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var string */
    private $limitPhrase;

    public function __construct(Definition $definition, Subject $subject, int $limit, string $phrase)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->limitPhrase = $phrase;
    }

    public function count(int $replaced, GroupAware $groupAware): void
    {
        preg::replace($this->definition->pattern, '', $this->subject->getSubject(), $this->limit + 1, $realCount);
        if ($realCount > $this->limit) {
            throw ReplacementExpectationFailedException::superfluous($realCount, $this->limit, $this->limitPhrase);
        }
    }
}
