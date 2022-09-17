<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\Counting\AtLeastCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\AtMostCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\ExactCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\SpecificReplacePatternImpl;
use TRegx\CleanRegex\Internal\Subject;

class LimitedReplacePattern implements SpecificReplacePattern
{
    /** @var SpecificReplacePattern */
    private $replacePattern;
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(SpecificReplacePattern $replacePattern, Definition $definition, Subject $subject, int $limit)
    {
        $this->replacePattern = $replacePattern;
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function with(string $replacement): string
    {
        return $this->replacePattern->with($replacement);
    }

    public function withReferences(string $replacement): string
    {
        return $this->replacePattern->withReferences($replacement);
    }

    public function callback(callable $callback): string
    {
        return $this->replacePattern->callback($callback);
    }

    public function exactly(): SpecificReplacePattern
    {
        return $this->replacePattern(new ExactCountingStrategy($this->definition, $this->subject, $this->limit));
    }

    public function atLeast(): SpecificReplacePattern
    {
        return $this->replacePattern(new AtLeastCountingStrategy($this->limit));
    }

    public function atMost(): SpecificReplacePattern
    {
        return $this->replacePattern(new AtMostCountingStrategy($this->definition, $this->subject, $this->limit));
    }

    private function replacePattern(CountingStrategy $countingStrategy): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl($this->definition, $this->subject, $this->limit, $countingStrategy);
    }
}
