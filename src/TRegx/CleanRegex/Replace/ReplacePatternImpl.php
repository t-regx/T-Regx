<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NonReplaced\NonMatchedMessage;
use TRegx\CleanRegex\Exception\CleanRegex\NotReplacedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\Map\MapReplacePattern;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\NonReplacedStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePattern§;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;

class ReplacePatternImpl implements ReplacePattern
{
    /** @var SpecificReplacePattern */
    private $replacePattern;
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var int */
    private $limit;
    /** @var ReplacePattern§ */
    private $replacePattern§;

    public function __construct(SpecificReplacePattern $replacePattern, InternalPattern $pattern, string $subject, int $limit, ReplacePattern§ $replacePattern§)
    {
        $this->replacePattern = $replacePattern;
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->replacePattern§ = $replacePattern§;
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

    public function by(): MapReplacePattern
    {
        return $this->replacePattern->by();
    }

    public function orThrow(string $exceptionClassName = NotReplacedException::class): SpecificReplacePattern
    {
        return $this->replacePattern(new ThrowStrategy($exceptionClassName, new NonMatchedMessage()));
    }

    public function orReturn($default): SpecificReplacePattern
    {
        return $this->replacePattern(new ConstantResultStrategy($default));
    }

    public function orElse(callable $producer): SpecificReplacePattern
    {
        return $this->replacePattern(new ComputedSubjectStrategy($producer));
    }

    private function replacePattern(NonReplacedStrategy $strategy): SpecificReplacePattern
    {
        return $this->replacePattern§->create($this->pattern, $this->subject, $this->limit, $strategy);
    }
}
