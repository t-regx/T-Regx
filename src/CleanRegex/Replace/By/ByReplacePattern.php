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
use TRegx\CleanRegex\Internal\Replace\Callback\CallbackInvoker;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Callback\GroupAwareSubstitute;

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

    public function __construct(GroupFallbackReplacer        $fallbackReplacer,
                                LazySubjectRs                $substitute,
                                PerformanceEmptyGroupReplace $performanceReplace,
                                Definition                   $definition,
                                int                          $limit,
                                CountingStrategy             $countingStrategy,
                                GroupAware                   $groupAware,
                                Subject                      $subject)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->substitute = $substitute;
        $this->subject = $subject;
        $this->performanceReplace = $performanceReplace;
        $this->definition = $definition;
        $this->limit = $limit;
        $this->countingStrategy = $countingStrategy;
        $this->groupAware = $groupAware;
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
            new CallbackInvoker($this->definition,
                $this->subject,
                $this->limit,
                $this->countingStrategy,
                new GroupAwareSubstitute($this->substitute, $group, $this->groupAware)),
            $group,
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
            new SubstituteFallbackMapper(new DictionaryMapper($map), $substitute),
            new ThrowMatchRs()); // ThrowMatchRs, because impossible for group 0 not to be matched
    }
}
