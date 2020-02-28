<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\NotReplacedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NonMatchedMessage;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\CustomThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePatternFactory;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;

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
    /** @var ReplacePatternFactory */
    private $replacePatternFactory;

    public function __construct(SpecificReplacePattern $replacePattern,
                                InternalPattern $pattern,
                                string $subject,
                                int $limit,
                                ReplacePatternFactory $replacePatternFactory)
    {
        $this->replacePattern = $replacePattern;
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->replacePatternFactory = $replacePatternFactory;
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

    public function by(): ByReplacePattern
    {
        return $this->replacePattern->by();
    }

    public function orThrow(string $exceptionClassName = NotReplacedException::class): SpecificReplacePattern
    {
        return $this->replacePattern(new CustomThrowStrategy($exceptionClassName, new NonMatchedMessage()));
    }

    public function orReturn($substitute): SpecificReplacePattern
    {
        return $this->replacePattern(new ConstantResultStrategy($substitute));
    }

    public function orElse(callable $substituteProducer): SpecificReplacePattern
    {
        return $this->replacePattern(new ComputedSubjectStrategy($substituteProducer));
    }

    private function replacePattern(ReplaceSubstitute $substitute): SpecificReplacePattern
    {
        return $this->replacePatternFactory->create($this->pattern, $this->subject, $this->limit, $substitute);
    }
}
