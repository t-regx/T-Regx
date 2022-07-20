<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\IdentityWrapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\ReplaceReferences;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\NaiveSubstitute;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\SafeRegex\preg;

class SpecificReplacePatternImpl implements SpecificReplacePattern, CompositeReplacePattern
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var SubjectRs */
    private $substitute;
    /** @var CountingStrategy */
    private $countingStrategy;

    public function __construct(Definition $definition, Subject $subject, int $limit, SubjectRs $substitute, CountingStrategy $countingStrategy)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
        $this->countingStrategy = $countingStrategy;
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::escaped($replacement));
    }

    public function withReferences(string $replacement): string
    {
        $result = preg::replace($this->definition->pattern, $replacement, $this->subject, $this->limit, $replaced);
        $this->countingStrategy->count($replaced, new LightweightGroupAware($this->definition));
        if ($replaced === 0) {
            return $this->substitute->substitute() ?? $result;
        }
        return $result;
    }

    public function callback(callable $callback): string
    {
        $invoker = new ReplacePatternCallbackInvoker($this->definition, $this->subject, $this->limit, $this->countingStrategy,
            new NaiveSubstitute($this->substitute));
        return $invoker->invoke($callback, new MatchStrategy());
    }

    /**
     * @deprecated
     */
    public function by(): ByReplacePattern
    {
        return new ByReplacePattern(
            new GroupFallbackReplacer(
                $this->definition,
                $this->subject,
                $this->limit,
                $this->substitute,
                $this->countingStrategy,
                new ApiBase($this->definition, $this->subject)),
            new LazyMessageThrowStrategy(),
            new PerformanceEmptyGroupReplace($this->definition, $this->subject, $this->limit),
            $this->definition,
            $this->limit,
            $this->countingStrategy,
            new LightweightGroupAware($this->definition),
            $this->subject,
            new IdentityWrapper());
    }

    /**
     * @deprecated
     */
    public function focus($nameOrIndex): FocusReplacePattern
    {
        return new FocusReplacePattern($this, $this->definition, $this->subject, $this->limit, GroupKey::of($nameOrIndex), $this->countingStrategy);
    }
}
