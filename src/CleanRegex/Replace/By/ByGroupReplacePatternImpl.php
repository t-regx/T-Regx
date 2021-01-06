<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\MapGroupMapperDecorator;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\SubstituteFallbackMapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
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

    public function map(array $occurrencesAndReplacements): UnmatchedGroupStrategy
    {
        return $this->performMap(new DictionaryMapper($occurrencesAndReplacements));
    }

    public function mapAndCallback(array $occurrencesAndReplacements, callable $mapper): UnmatchedGroupStrategy
    {
        return $this->performMap(new MapGroupMapperDecorator(new DictionaryMapper($occurrencesAndReplacements), $mapper));
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

    public function mapIfExists(array $occurrencesAndReplacements): UnmatchedGroupStrategy
    {
        return new UnmatchedGroupStrategy(
            $this->fallbackReplacer,
            $this->nameOrIndex,
            new IgnoreMessages(new WrappingMapper(new DictionaryMapper($occurrencesAndReplacements), $this->middlewareMapper)),
            $this->middlewareMapper);
    }

    public function orElseThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replaceGroupOptional(new ThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function orElseWith(string $replacement): string
    {
        return $this->replaceGroupOptional(new ConstantReturnStrategy($replacement));
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
            new IgnoreMessages(new WrappingMapper(new IdentityMapper(), $this->middlewareMapper)),
            new WrappingMatchRs($substitute, $this->middlewareMapper)
        );
    }

    public function callback(callable $callback): string
    {
        return $this->replaceCallbackInvoker->invoke($callback, new MatchGroupStrategy($this->nameOrIndex));
    }
}
