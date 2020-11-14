<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\NotReplacedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NonReplacedMessage;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\OtherwiseStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;

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

    public function __construct(SpecificReplacePattern $replacePattern, InternalPattern $pattern, string $subject, int $limit)
    {
        $this->replacePattern = $replacePattern;
        $this->pattern = $pattern;
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

    public function by(): ByReplacePattern
    {
        return $this->replacePattern->by();
    }

    public function otherwiseThrowing(string $exceptionClassName = null): SpecificReplacePattern
    {
        return $this->replacePattern(new ThrowStrategy($exceptionClassName ?? NotReplacedException::class, new NonReplacedMessage()));
    }

    public function otherwiseReturning($substitute): SpecificReplacePattern
    {
        return $this->replacePattern(new ConstantReturnStrategy($substitute));
    }

    public function otherwise(callable $substituteProducer): SpecificReplacePattern
    {
        return $this->replacePattern(new OtherwiseStrategy($substituteProducer));
    }

    private function replacePattern(SubjectRs $substitute): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl($this->pattern, $this->subject, $this->limit, $substitute);
    }
}
