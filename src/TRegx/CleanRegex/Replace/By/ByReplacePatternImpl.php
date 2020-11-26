<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Internal\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\SubstituteFallbackMapper;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\Wrapper;
use TRegx\CleanRegex\Internal\Replace\GroupMapper\WrappingMapper;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\LazySubjectRs;
use TRegx\CleanRegex\Internal\Replace\NonReplaced\ThrowMatchRs;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;

class ByReplacePatternImpl implements ByReplacePattern
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var LazySubjectRs */
    private $substitute;
    /** @var string */
    private $subject;
    /** @var PerformanceEmptyGroupReplace */
    private $performanceReplace;
    /** @var ReplacePatternCallbackInvoker */
    private $replaceCallbackInvoker;
    /** @var Wrapper */
    private $wrapper;

    public function __construct(GroupFallbackReplacer $fallbackReplacer,
                                LazySubjectRs $substitute,
                                PerformanceEmptyGroupReplace $performanceReplace,
                                ReplacePatternCallbackInvoker $replaceCallbackInvoker,
                                string $subject,
                                Wrapper $middlewareMapper)
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
        (new GroupNameValidator($nameOrIndex))->validate();
        return new ByGroupReplacePatternImpl(
            $this->fallbackReplacer,
            $this->performanceReplace,
            $this->replaceCallbackInvoker,
            $nameOrIndex,
            $this->subject,
            $this->wrapper);
    }

    public function map(array $map): string
    {
        return $this->replace($map, $this->substitute);
    }

    public function mapIfExists(array $map): string
    {
        return $this->replace($map, new DefaultStrategy());
    }

    private function replace(array $map, LazySubjectRs $substitute): string
    {
        return $this->fallbackReplacer->replaceOrFallback(
            0,
            new SubstituteFallbackMapper(
                new WrappingMapper(new DictionaryMapper($map), $this->wrapper),
                $substitute,
                $this->subject),
            new ThrowMatchRs()); // ThrowMatchRs, because impossible for group 0 not to be matched
    }
}
