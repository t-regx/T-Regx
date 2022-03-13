<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\CallbackCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;

abstract class ReplacePatternImpl implements ReplacePattern
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

    public function by(): ByReplacePattern
    {
        return $this->replacePattern->by();
    }

    public function counting(callable $countReceiver): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new CallbackCountingStrategy($countReceiver, $this->subject));
    }

    protected function replacePattern(SubjectRs $substitute, CountingStrategy $countingStrategy): CompositeReplacePattern
    {
        return new SpecificReplacePatternImpl($this->definition, $this->subject, $this->limit, $substitute, $countingStrategy);
    }

    /**
     * @deprecated
     */
    public function focus($nameOrIndex): FocusReplacePattern
    {
        return new FocusReplacePattern($this->replacePattern, $this->definition, $this->subject, $this->limit, GroupKey::of($nameOrIndex), new IgnoreCounting());
    }
}
