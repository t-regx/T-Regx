<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\By\LazyDetail;
use TRegx\SafeRegex\preg;

class GroupFallbackReplacer
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var SubjectRs */
    private $substitute;
    /** @var CountingStrategy */
    private $countingStrategy;
    /** @var Base */
    private $base;
    /** @var int */
    private $counter = -1;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(Definition $definition, Subject $subject, int $limit, SubjectRs $substitute, CountingStrategy $countingStrategy, Base $base)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
        $this->countingStrategy = $countingStrategy;
        $this->base = $base;
        $this->groupAware = new LightweightGroupAware($definition);
    }

    public function replaceOrFallback(GroupKey $group, DetailGroupMapper $mapper, MatchRs $substitute): string
    {
        $this->counter = -1;
        return $this->replaceUsingCallback(function (array $match) use ($group, $mapper, $substitute): string {
            $this->counter++;
            $this->validateGroup($match, $group);
            return $this->getReplacementOrHandle($match, $group, $mapper, $substitute);
        });
    }

    private function replaceUsingCallback(callable $closure): string
    {
        $result = $this->pregReplaceCallback($closure, $replaced);
        $this->countingStrategy->count($replaced, $this->groupAware);
        if ($replaced === 0) {
            return $this->substitute->substitute() ?? $result;
        }
        return $result;
    }

    private function pregReplaceCallback(callable $closure, ?int &$replaced): string
    {
        return preg::replace_callback(
            $this->definition->pattern,
            $closure,
            $this->subject,
            $this->limit,
            $replaced);
    }

    private function validateGroup(array $match, GroupKey $group): void
    {
        if (!array_key_exists($group->nameOrIndex(), $match)) {
            if (!$this->base->matchAllOffsets()->hasGroup($group)) {
                throw new NonexistentGroupException($group);
            }
        }
    }

    private function getReplacementOrHandle(array $match, GroupKey $group, DetailGroupMapper $mapper, MatchRs $substitute): string
    {
        $occurrence = $this->occurrence($match, $group);
        $detail = new LazyDetail($this->base, $this->subject, $this->counter, $this->limit);
        if ($occurrence === null) { // here "null" means group was not matched
            $replacement = $substitute->substituteGroup($detail);
            // here "null" means "no replacement provided, ignore me, use the full match"
            return $replacement ?? $match[0];
        }
        $mapper->useExceptionValues($occurrence, $group, $match[0]);
        return $mapper->map($occurrence, $detail) ?? $match[0];
    }

    private function occurrence(array $match, GroupKey $group): ?string
    {
        if (array_key_exists($group->nameOrIndex(), $match)) {
            return $this->makeSureOccurrence($group, $match[$group->nameOrIndex()]);
        }
        return null;
    }

    private function makeSureOccurrence(GroupKey $group, string $occurrence): ?string
    {
        if ($occurrence !== '') {
            return $occurrence;
        }
        // With preg_replace_callback - it's impossible to distinguish unmatched group from a matched empty string
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup(new GroupIndex($this->counter))) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        if (!$matches->isGroupMatched($group->nameOrIndex(), $this->counter)) {
            return null;
        }
        return $occurrence;
    }
}
