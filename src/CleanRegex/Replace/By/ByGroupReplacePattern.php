<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\MapGroupMapperDecorator;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\SubstituteFallbackMapper;
use TRegx\CleanRegex\Internal\Replace\By\IgnoreMessages;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\By\UnmatchedGroupStrategy;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMatchRs;
use TRegx\CleanRegex\Replace\Callback\MatchGroupStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\GroupReplace;

/**
 * @deprecated
 */
class ByGroupReplacePattern implements GroupReplace
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var GroupKey */
    private $group;
    /** @var PerformanceEmptyGroupReplace */
    private $performanceReplace;
    /** @var ReplacePatternCallbackInvoker */
    private $replaceCallbackInvoker;
    /** @var Wrapper */
    private $middlewareMapper;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(GroupFallbackReplacer         $fallbackReplacer,
                                PerformanceEmptyGroupReplace  $performanceReplace,
                                ReplacePatternCallbackInvoker $replaceCallbackInvoker,
                                GroupKey                      $group,
                                Wrapper                       $middlewareMapper,
                                GroupAware                    $groupAware)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->group = $group;
        $this->performanceReplace = $performanceReplace;
        $this->replaceCallbackInvoker = $replaceCallbackInvoker;
        $this->middlewareMapper = $middlewareMapper;
        $this->groupAware = $groupAware;
    }

    /**
     * @deprecated
     */
    public function map(array $occurrencesAndReplacements): GroupReplace
    {
        return $this->performMap(new DictionaryMapper($occurrencesAndReplacements));
    }

    /**
     * @deprecated
     */
    public function mapAndCallback(array $occurrencesAndReplacements, callable $mapper): GroupReplace
    {
        return $this->performMap(new MapGroupMapperDecorator(new DictionaryMapper($occurrencesAndReplacements), $mapper));
    }

    private function performMap(GroupMapper $mapper): GroupReplace
    {
        return new UnmatchedGroupStrategy(
            $this->fallbackReplacer,
            $this->group,
            new SubstituteFallbackMapper(
                new WrappingMapper($mapper, $this->middlewareMapper),
                new LazyMessageThrowStrategy()),
            $this->middlewareMapper);
    }

    /**
     * @deprecated
     */
    public function mapIfExists(array $occurrencesAndReplacements): GroupReplace
    {
        return new UnmatchedGroupStrategy(
            $this->fallbackReplacer,
            $this->group,
            new IgnoreMessages(new WrappingMapper(new DictionaryMapper($occurrencesAndReplacements), $this->middlewareMapper)),
            $this->middlewareMapper);
    }

    /**
     * @deprecated
     */
    public function orElseThrow(\Throwable $throwable = null): string
    {
        return $this->replaceGroupOptional(new ThrowStrategy($throwable, $this->group));
    }

    /**
     * @deprecated
     */
    public function orElseWith(string $replacement): string
    {
        return $this->replaceGroupOptional(new ConstantReturnStrategy($replacement));
    }

    /**
     * @deprecated
     */
    public function orElseIgnore(): string
    {
        return $this->replaceGroupOptional(new DefaultStrategy());
    }

    /**
     * @deprecated
     */
    public function orElseEmpty(): string
    {
        if (\is_int($this->group->nameOrIndex())) {
            return $this->performanceReplace->replaceWithGroupOrEmpty($this->group->nameOrIndex());
        }
        return $this->replaceGroupOptional(new ConstantReturnStrategy(''));
    }

    /**
     * @deprecated
     */
    public function orElseCalling(callable $replacementProducer): string
    {
        return $this->replaceGroupOptional(new ComputedMatchStrategy($replacementProducer, "orElseCalling"));
    }

    private function replaceGroupOptional(MatchRs $substitute): string
    {
        return $this->fallbackReplacer->replaceOrFallback($this->group,
            new IgnoreMessages(new WrappingMapper(new IdentityMapper(), $this->middlewareMapper)),
            new WrappingMatchRs($substitute, $this->middlewareMapper));
    }

    /**
     * @deprecated
     */
    public function callback(callable $callback): string
    {
        if ($this->groupExists()) {
            return $this->replaceCallbackInvoker->invoke($callback, new MatchGroupStrategy($this->group));
        }
        throw new NonexistentGroupException($this->group);
    }

    private function groupExists(): bool
    {
        if ($this->group->nameOrIndex() === 0) {
            return true;
        }
        return $this->groupAware->hasGroup($this->group);
    }
}
