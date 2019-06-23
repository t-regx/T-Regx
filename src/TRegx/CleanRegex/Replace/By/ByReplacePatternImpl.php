<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Replace\GroupMapper\StrategyFallbackAdapter;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;

class ByReplacePatternImpl implements ByReplacePattern
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var ReplaceSubstitute */
    private $substitute;
    /** @var string */
    private $subject;
    /** @var PerformanceEmptyGroupReplace */
    private $performanceReplace;
    /** @var ReplacePatternCallbackInvoker */
    private $replaceCallbackInvoker;

    public function __construct(GroupFallbackReplacer $fallbackReplacer,
                                ReplaceSubstitute $substitute,
                                PerformanceEmptyGroupReplace $performanceReplace,
                                ReplacePatternCallbackInvoker $replaceCallbackInvoker,
                                string $subject)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->substitute = $substitute;
        $this->subject = $subject;
        $this->performanceReplace = $performanceReplace;
        $this->replaceCallbackInvoker = $replaceCallbackInvoker;
    }

    public function group($nameOrIndex): ByGroupReplacePattern
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return new ByGroupReplacePatternImpl(
            $this->fallbackReplacer,
            $this->performanceReplace,
            $this->replaceCallbackInvoker,
            $nameOrIndex,
            $this->subject);
    }

    public function map(array $map): string
    {
        return $this->replace($map, $this->substitute);
    }

    public function mapIfExists(array $map): string
    {
        return $this->replace($map, new DefaultStrategy());
    }

    public function replace(array $map, ReplaceSubstitute $substitute): string
    {
        return $this->fallbackReplacer->replaceOrFallback(
            0,
            new StrategyFallbackAdapter(new DictionaryMapper($map), $substitute, $this->subject),
            LazyMessageThrowStrategy::internalException());
    }
}
