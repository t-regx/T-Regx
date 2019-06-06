<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Exception\CleanRegex\MissingReplacementKeyException;
use TRegx\CleanRegex\Replace\GroupMapper\DictionaryMapper;
use TRegx\CleanRegex\Replace\GroupMapper\IdentityMapper;
use TRegx\CleanRegex\Replace\GroupMapper\StrategyFallbackAdapter;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\CustomThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;
use TRegx\CleanRegex\Replace\NonReplaced\LazyMessageThrowStrategy;

class ByGroupReplacePatternImpl implements ByGroupReplacePattern
{
    /** @var GroupFallbackReplacer */
    private $fallbackReplacer;
    /** @var string|int */
    private $nameOrIndex;
    /** @var string */
    private $subject;

    public function __construct(GroupFallbackReplacer $fallbackReplacer, $nameOrIndex, string $subject)
    {
        $this->fallbackReplacer = $fallbackReplacer;
        $this->nameOrIndex = $nameOrIndex;
        $this->subject = $subject;
    }

    public function map(array $map): OptionalStrategySelector
    {
        return new OptionalStrategySelectorImpl(
            $this->fallbackReplacer,
            $this->nameOrIndex,
            new StrategyFallbackAdapter(
                new DictionaryMapper($map),
                new LazyMessageThrowStrategy(MissingReplacementKeyException::class),
                $this->subject)
        );
    }

    public function mapIfExists(array $map): OptionalStrategySelector
    {
        return new OptionalStrategySelectorImpl($this->fallbackReplacer, $this->nameOrIndex, new DictionaryMapper($map));
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replaceGroupOptional(new CustomThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function orReturn($substitute): string
    {
        return $this->replaceGroupOptional(new ConstantResultStrategy($substitute));
    }

    public function orElse(callable $substituteProducer): string
    {
        return $this->replaceGroupOptional(new ComputedSubjectStrategy($substituteProducer));
    }

    public function replaceGroupOptional(ReplaceSubstitute $substitute): string
    {
        if ($this->nameOrIndex === 0) {
            throw new InternalCleanRegexException();
        }
        return $this->fallbackReplacer->replaceOrFallback($this->nameOrIndex, new IdentityMapper(), $substitute);
    }
}
