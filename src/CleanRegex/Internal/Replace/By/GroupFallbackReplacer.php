<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\LazyDetail;
use TRegx\SafeRegex\preg;
use function array_key_exists;

class GroupFallbackReplacer
{
    /** @var Definition */
    private $definition;
    /** @var Subjectable */
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

    public function __construct(Definition $definition, Subjectable $subject, int $limit, SubjectRs $substitute, CountingStrategy $countingStrategy, Base $base)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
        $this->countingStrategy = $countingStrategy;
        $this->base = $base;
    }

    public function replaceOrFallback(GroupKey $groupId, DetailGroupMapper $mapper, MatchRs $substitute): string
    {
        $this->counter = -1;
        return $this->replaceUsingCallback(function (array $match) use ($groupId, $mapper, $substitute): string {
            $this->counter++;
            $this->validateGroup($match, $groupId);
            return $this->getReplacementOrHandle($match, $groupId, $mapper, $substitute);
        });
    }

    private function replaceUsingCallback(callable $closure): string
    {
        $result = $this->pregReplaceCallback($closure, $replaced);
        $this->countingStrategy->count($replaced);
        if ($replaced === 0) {
            return $this->substitute->substitute($this->subject->getSubject()) ?? $result;
        }
        return $result;
    }

    private function pregReplaceCallback(callable $closure, ?int &$replaced): string
    {
        return preg::replace_callback(
            $this->definition->pattern,
            $closure,
            $this->subject->getSubject(),
            $this->limit,
            $replaced);
    }

    private function validateGroup(array $match, GroupKey $groupId): void
    {
        if (!array_key_exists($groupId->nameOrIndex(), $match)) {
            if (!$this->base->matchAllOffsets()->hasGroup($groupId->nameOrIndex())) {
                throw new NonexistentGroupException($groupId);
            }
        }
    }

    private function getReplacementOrHandle(array $match, GroupKey $groupId, DetailGroupMapper $mapper, MatchRs $substitute): string
    {
        $occurrence = $this->occurrence($match, $groupId);
        $detail = new LazyDetail($this->base, $this->counter, $this->limit);
        if ($occurrence === null) { // here "null" means group was not matched
            $replacement = $substitute->substituteGroup($detail);
            // here "null" means "no replacement provided, ignore me, use the full match"
            return $replacement ?? $match[0];
        }
        $mapper->useExceptionValues($occurrence, $groupId, $match[0]);
        return $mapper->map($occurrence, $detail) ?? $match[0];
    }

    private function occurrence(array $match, GroupKey $groupId): ?string
    {
        if (array_key_exists($groupId->nameOrIndex(), $match)) {
            return $this->makeSureOccurrence($groupId, $match[$groupId->nameOrIndex()]);
        }
        return null;
    }

    private function makeSureOccurrence(GroupKey $groupId, string $occurrence): ?string
    {
        if ($occurrence !== '') {
            return $occurrence;
        }
        // With preg_replace_callback - it's impossible to distinguish unmatched group from a matched empty string
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->counter)) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        if (!$matches->isGroupMatched($groupId->nameOrIndex(), $this->counter)) {
            return null;
        }
        return $occurrence;
    }
}
