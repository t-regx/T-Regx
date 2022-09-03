<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\SpecificReplacePatternImpl;
use TRegx\CleanRegex\Internal\Subject;

abstract class ReplacePatternImpl implements SpecificReplacePattern
{
    /** @var SpecificReplacePattern */
    private $replacePattern;
    /** @var Definition */
    protected $definition;
    /** @var Subject */
    protected $subject;
    /** @var int */
    protected $limit;

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

    protected function replacePattern(CountingStrategy $countingStrategy): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl($this->definition, $this->subject, $this->limit, $countingStrategy);
    }
}
