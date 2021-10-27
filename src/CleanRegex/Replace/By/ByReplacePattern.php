<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\WholeMatch;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\SubstituteFallbackMapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\LazySubjectRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowMatchRs;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMapper;
use TRegx\CleanRegex\Internal\Subject;
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
    /** @var ReplacePatternCallbackInvoker */
    private $replaceCallbackInvoker;
    /** @var Wrapper */
    private $wrapper;

    public function __construct(GroupFallbackReplacer         $fallbackReplacer,
                                LazySubjectRs                 $substitute,
                                PerformanceEmptyGroupReplace  $performanceReplace,
                                ReplacePatternCallbackInvoker $replaceCallbackInvoker,
                                Subject                       $subject,
                                Wrapper                       $middlewareMapper)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->substitute = $substitute;
        $this->subject = $subject;
        $this->performanceReplace = $performanceReplace;
        $this->replaceCallbackInvoker = $replaceCallbackInvoker;
        $this->wrapper = $middlewareMapper;
    }

    public function group($nameOrIndex): ByGroupReplacePattern
    {
        return new ByGroupReplacePattern(
            $this->fallbackReplacer,
            $this->performanceReplace,
            $this->replaceCallbackInvoker,
            GroupKey::of($nameOrIndex),
            $this->subject,
            $this->wrapper);
    }

    public function map(array $occurrencesAndReplacements): string
    {
        return $this->replace($occurrencesAndReplacements, $this->substitute);
    }

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
                $substitute,
                $this->subject),
            new ThrowMatchRs()); // ThrowMatchRs, because impossible for group 0 not to be matched
    }
}
