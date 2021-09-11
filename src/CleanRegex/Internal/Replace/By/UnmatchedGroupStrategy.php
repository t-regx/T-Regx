<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\Replace\Wrapper;
use TRegx\CleanRegex\Internal\Replace\WrappingMatchRs;
use TRegx\CleanRegex\Replace\GroupReplace;

class UnmatchedGroupStrategy implements GroupReplace
{
    /** @var GroupFallbackReplacer */
    private $replacer;
    /** @var GroupKey */
    private $group;
    /** @var GroupMapper */
    private $mapper;
    /** @var Wrapper */
    private $middlewareMapper;

    public function __construct(GroupFallbackReplacer $replacer, GroupKey $group, DetailGroupMapper $mapper, Wrapper $middlewareMapper)
    {
        $this->replacer = $replacer;
        $this->group = $group;
        $this->mapper = $mapper;
        $this->middlewareMapper = $middlewareMapper;
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
            new ReplacementWithUnmatchedGroupMessage($this->group)));
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
        return $this->replacer->replaceOrFallback($this->group, $this->mapper, new WrappingMatchRs($substitute, $this->middlewareMapper));
    }
}
