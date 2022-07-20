<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\WholeMatch;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\SubstituteFallbackMapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazySubjectRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowMatchRs;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMapper;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Callback\GroupAwareSubstitute;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

class ByReplacePattern
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var LazySubjectRs */
    private $substitute;
    /** @var Subject */
    private $subject;
    /** @var PerformanceEmptyGroupReplace */
    private $performanceReplace;
    /** @var Definition */
    private $definition;
    /** @var int */
    private $limit;
    /** @var CountingStrategy */
    private $countingStrategy;
    /** @var GroupAware */
    private $groupAware;
    /** @var Wrapper */
    private $wrapper;

    public function __construct(GroupFallbackReplacer        $fallbackReplacer,
                                LazySubjectRs                $substitute,
                                PerformanceEmptyGroupReplace $performanceReplace,
                                Definition                   $definition,
                                int                          $limit,
                                CountingStrategy             $countingStrategy,
                                GroupAware                   $groupAware,
                                Subject                      $subject,
                                Wrapper                      $middlewareMapper)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->substitute = $substitute;
        $this->subject = $subject;
        $this->performanceReplace = $performanceReplace;
        $this->definition = $definition;
        $this->limit = $limit;
        $this->countingStrategy = $countingStrategy;
        $this->groupAware = $groupAware;
        $this->wrapper = $middlewareMapper;
    }

    /**
     * @deprecated
     */
    public function group($nameOrIndex): ByGroupReplacePattern
    {
        return $this->replaceGroup(GroupKey::of($nameOrIndex));
    }

    private function replaceGroup(GroupKey $group): ByGroupReplacePattern
    {
        return new ByGroupReplacePattern(
            $this->fallbackReplacer,
            $this->performanceReplace,
            new ReplacePatternCallbackInvoker($this->definition,
                $this->subject,
                $this->limit,
                $this->countingStrategy,
                new GroupAwareSubstitute($this->substitute, $group, $this->groupAware)),
            $group,
            $this->wrapper,
            $this->groupAware);
    }

    /**
     * @deprecated
     */
    public function map(array $occurrencesAndReplacements): string
    {
        return $this->replace($occurrencesAndReplacements, $this->substitute);
    }

    /**
     * @deprecated
     */
    public function mapIfExists(array $occurrencesAndReplacements): string
    {
        return $this->replace($occurrencesAndReplacements, new DefaultStrategy());
    }

    private function replace(array $map, LazySubjectRs $substitute): string
    {
        return $this->fallbackReplacer->replaceOrFallback(
            new WholeMatch(),
            new SubstituteFallbackMapper(
                new WrappingMapper(new DictionaryMapper($map), $this->wrapper),
                $substitute),
            new ThrowMatchRs()); // ThrowMatchRs, because impossible for group 0 not to be matched
    }
}
