<?php
namespace TRegx\CleanRegex\Replace\Map;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\NonReplacedStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;

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

    public function orReturn($default): string
    {
        return $this->replace(new ConstantResultStrategy($default));
    }

    public function orElse(callable $producer): string
    {
        return $this->replace(new ComputedSubjectStrategy($producer));
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replace(new ThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function replace(NonReplacedStrategy $nonReplacedStrategy): string
    {
        return $this->replacer->replaceOrFallback($this->nameOrIndex, $this->mapper, $nonReplacedStrategy);
    }
}
