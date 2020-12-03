<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\MapGroupMapperDecorator;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\SubstituteFallbackMapper;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMatchRs;
use TRegx\CleanRegex\Replace\Callback\MatchGroupStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

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
    /** @var Wrapper */
    private $middlewareMapper;

    public function __construct(GroupFallbackReplacer $fallbackReplacer,
                                PerformanceEmptyGroupReplace $performanceReplace,
                                ReplacePatternCallbackInvoker $replaceCallbackInvoker,
                                $nameOrIndex,
                                string $subject,
                                Wrapper $middlewareMapper)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->nameOrIndex = $nameOrIndex;
        $this->subject = $subject;
        $this->performanceReplace = $performanceReplace;
        $this->replaceCallbackInvoker = $replaceCallbackInvoker;
        $this->middlewareMapper = $middlewareMapper;
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
            new SubstituteFallbackMapper(new WrappingMapper($mapper, $this->middlewareMapper),
                new LazyMessageThrowStrategy(MissingReplacementKeyException::class), $this->subject),
            $this->middlewareMapper
        );
    }

    public function mapIfExists(array $map): UnmatchedGroupStrategy
    {
        return new UnmatchedGroupStrategy(
            $this->fallbackReplacer,
            $this->nameOrIndex,
            new WrappingMapper(new DictionaryMapper($map), $this->middlewareMapper),
            $this->middlewareMapper);
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
        return $this->fallbackReplacer->replaceOrFallback($this->nameOrIndex,
            new WrappingMapper(new IdentityMapper(), $this->middlewareMapper),
            new WrappingMatchRs($substitute, $this->middlewareMapper)
        );
    }

    public function callback(callable $callback): string
    {
        return $this->replaceCallbackInvoker->invoke($callback, new MatchGroupStrategy($this->nameOrIndex));
    }
}
