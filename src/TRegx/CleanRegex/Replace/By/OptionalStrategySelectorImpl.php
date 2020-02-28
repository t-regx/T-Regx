<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\CustomThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;

class OptionalStrategySelectorImpl implements OptionalStrategySelector
{
    /** @var GroupFallbackReplacer */
    private $replacer;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupMapper */
    private $mapper;

    public function __construct(GroupFallbackReplacer $replacer, $nameOrIndex, GroupMapper $mapper)
    {
        $this->replacer = $replacer;
        $this->nameOrIndex = $nameOrIndex;
        $this->mapper = $mapper;
    }

    public function orReturn($substitute): string
    {
        return $this->replace(new ConstantResultStrategy($substitute));
    }

    public function orElse(callable $substituteProducer): string
    {
        return $this->replace(new ComputedSubjectStrategy($substituteProducer));
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replace(new CustomThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function replace(ReplaceSubstitute $substitute): string
    {
        return $this->replacer->replaceOrFallback($this->nameOrIndex, $this->mapper, $substitute);
    }
}
