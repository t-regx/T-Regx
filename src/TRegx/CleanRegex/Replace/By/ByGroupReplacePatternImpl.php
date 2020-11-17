<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Replace\Callback\MatchGroupStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Replace\GroupMapper\MapGroupMapperDecorator;
use TRegx\CleanRegex\Replace\GroupMapper\StrategyFallbackAdapter;

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

    public function map(array $map): UnmatchedGroupStrategy
    {
        return $this->performMap(new DictionaryMapper($map));
    }

    public function mapAndCallback(array $map, callable $mapper): UnmatchedGroupStrategy
    {
        return $this->performMap(new MapGroupMapperDecorator(new DictionaryMapper($map), $mapper));
    }

    private function performMap(GroupMapper $mapper): UnmatchedGroupStrategy
    {
        return new UnmatchedGroupStrategy(
            $this->fallbackReplacer,
            $this->nameOrIndex,
            new StrategyFallbackAdapter($mapper,
                new LazyMessageThrowStrategy(MissingReplacementKeyException::class), $this->subject)
        );
    }

    public function mapIfExists(array $map): UnmatchedGroupStrategy
    {
        return new UnmatchedGroupStrategy($this->fallbackReplacer, $this->nameOrIndex, new DictionaryMapper($map));
    }

    public function orElseThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replaceGroupOptional(new ThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function orElseWith(string $substitute): string
    {
        return $this->replaceGroupOptional(new ConstantReturnStrategy($substitute));
    }

    public function orElseIgnore(): string
    {
        return $this->replaceGroupOptional(new DefaultStrategy());
    }

    public function orElseEmpty(): string
    {
        if (\is_int($this->nameOrIndex)) {
            return $this->performanceReplace->replaceWithGroupOrEmpty($this->nameOrIndex);
        }
        return $this->replaceGroupOptional(new ConstantReturnStrategy(''));
    }

    public function orElseCalling(callable $replacementProducer): string
    {
        return $this->replaceGroupOptional(new ComputedMatchStrategy($replacementProducer, "orElseCalling"));
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
