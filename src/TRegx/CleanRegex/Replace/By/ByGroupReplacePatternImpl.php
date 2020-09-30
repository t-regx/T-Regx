<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Replace\Callback\MatchGroupStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Replace\GroupMapper\MapGroupMapperDecorator;
use TRegx\CleanRegex\Replace\GroupMapper\StrategyFallbackAdapter;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\MatchRs;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;

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
        return $this->performMap(new DictionaryMapper($map));
    }

    public function mapAndCallback(array $map, callable $mapper): OptionalStrategySelector
    {
        return $this->performMap(new MapGroupMapperDecorator(new DictionaryMapper($map), $mapper));
    }

    private function performMap(GroupMapper $mapper): OptionalStrategySelectorImpl
    {
        return new OptionalStrategySelectorImpl(
            $this->fallbackReplacer,
            $this->nameOrIndex,
            new StrategyFallbackAdapter($mapper,
                new LazyMessageThrowStrategy(MissingReplacementKeyException::class), $this->subject)
        );
    }

    public function mapIfExists(array $map): OptionalStrategySelector
    {
        return new OptionalStrategySelectorImpl($this->fallbackReplacer, $this->nameOrIndex, new DictionaryMapper($map));
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replaceGroupOptional(new ThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function orReturn($substitute): string
    {
        return $this->replaceGroupOptional(new ConstantReturnStrategy($substitute));
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
        return $this->replaceGroupOptional(new ConstantReturnStrategy(''));
    }

    public function orElse(callable $substituteProducer): string
    {
        return $this->replaceGroupOptional(new ComputedMatchStrategy($substituteProducer));
    }

    private function replaceGroupOptional(MatchRs $substitute): string
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
