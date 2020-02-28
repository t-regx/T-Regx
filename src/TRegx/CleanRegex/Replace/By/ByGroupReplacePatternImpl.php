<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Replace\Callback\MatchGroupStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Replace\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Replace\GroupMapper\StrategyFallbackAdapter;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\CustomThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;

class ByGroupReplacePatternImpl implements ByGroupReplacePattern
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var string|int */
    private $nameOrIndex;
    /** @var string */
    private $subject;
    /** @var PerformanceEmptyGroupReplace */
    private $performanceReplace;
    /** @var ReplacePatternCallbackInvoker */
    private $replaceCallbackInvoker;

    public function __construct(GroupFallbackReplacer $fallbackReplacer,
                                PerformanceEmptyGroupReplace $performanceReplace,
                                ReplacePatternCallbackInvoker $replaceCallbackInvoker,
                                $nameOrIndex,
                                string $subject)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->nameOrIndex = $nameOrIndex;
        $this->subject = $subject;
        $this->performanceReplace = $performanceReplace;
        $this->replaceCallbackInvoker = $replaceCallbackInvoker;
    }

    public function map(array $map): OptionalStrategySelector
    {
        return new OptionalStrategySelectorImpl(
            $this->fallbackReplacer,
            $this->nameOrIndex,
            new StrategyFallbackAdapter(
                new DictionaryMapper($map),
                new LazyMessageThrowStrategy(MissingReplacementKeyException::class),
                $this->subject)
        );
    }

    public function mapIfExists(array $map): OptionalStrategySelector
    {
        return new OptionalStrategySelectorImpl($this->fallbackReplacer, $this->nameOrIndex, new DictionaryMapper($map));
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replaceGroupOptional(new CustomThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function orReturn($substitute): string
    {
        return $this->replaceGroupOptional(new ConstantResultStrategy($substitute));
    }

    public function orIgnore(): string
    {
        return $this->replaceGroupOptional(new DefaultStrategy());
    }

    public function orEmpty(): string
    {
        if (\is_int($this->nameOrIndex)) {
            return $this->performanceReplace->replaceWithGroupOrEmpty($this->nameOrIndex);
        }
        return $this->replaceGroupOptional(new ConstantResultStrategy(''));
    }

    public function orElse(callable $substituteProducer): string
    {
        return $this->replaceGroupOptional(new ComputedSubjectStrategy($substituteProducer));
    }

    public function replaceGroupOptional(ReplaceSubstitute $substitute): string
    {
        if ($this->nameOrIndex === 0) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        return $this->fallbackReplacer->replaceOrFallback($this->nameOrIndex, new IdentityMapper(), $substitute);
    }

    public function callback(callable $callback): string
    {
        return $this->replaceCallbackInvoker->invoke($callback, new MatchGroupStrategy($this->nameOrIndex));
    }
}
