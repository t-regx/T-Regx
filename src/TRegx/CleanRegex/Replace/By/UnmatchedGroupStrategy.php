<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\GroupReplace;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\MatchRs;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;

class UnmatchedGroupStrategy implements GroupReplace
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

    public function orElseWith(string $replacement): string
    {
        return $this->replace(new ConstantReturnStrategy($replacement));
    }

    public function orElseCalling(callable $replacementProducer): string
    {
        return $this->replace(new ComputedMatchStrategy($replacementProducer, "orElseCalling"));
    }

    public function orElseThrow(string $exceptionClassName = null): string
    {
        return $this->replace(new ThrowStrategy(
            $exceptionClassName ?? GroupNotMatchedException::class,
            new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function orElseIgnore(): string
    {
        return $this->replace(new DefaultStrategy());
    }

    public function orElseEmpty(): string
    {
        return $this->replace(new ConstantReturnStrategy(''));
    }

    private function replace(MatchRs $substitute): string
    {
        return $this->replacer->replaceOrFallback($this->nameOrIndex, $this->mapper, $substitute);
    }
}
