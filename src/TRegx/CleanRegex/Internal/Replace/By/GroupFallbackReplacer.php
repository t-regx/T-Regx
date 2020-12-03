<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\LazyDetailImpl;
use TRegx\SafeRegex\preg;
use function array_key_exists;

class GroupFallbackReplacer
{
    /** @var Pattern */
    private $pattern;
    /** @var Subjectable */
    private $subject;
    /** @var int */
    private $limit;
    /** @var SubjectRs */
    private $substitute;
    /** @var Base */
    private $base;
    /** @var int */
    private $counter = -1;

    public function __construct(Pattern $pattern, Subjectable $subject, int $limit, SubjectRs $substitute, Base $base)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
        $this->base = $base;
    }

    public function replaceOrFallback($nameOrIndex, GroupMapper $mapper, MatchRs $substitute): string
    {
        $this->counter = -1;
        return $this->replaceUsingCallback(function (array $match) use ($nameOrIndex, $mapper, $substitute) {
            $this->counter++;
            $this->validateGroup($match, $nameOrIndex);
            return $this->getReplacementOrHandle($match, $nameOrIndex, $mapper, $substitute);
        });
    }

    private function replaceUsingCallback(callable $closure): string
    {
        $result = $this->pregReplaceCallback($closure, $replaced);
        if ($replaced === 0) {
            return $this->substitute->substitute($this->subject->getSubject()) ?? $result;
        }
        return $result;
    }

    private function pregReplaceCallback(callable $closure, ?int &$replaced): string
    {
        return preg::replace_callback(
            $this->pattern->pattern,
            $closure,
            $this->subject->getSubject(),
            $this->limit,
            $replaced);
    }

    private function validateGroup(array $match, $nameOrIndex): void
    {
        if (!array_key_exists($nameOrIndex, $match)) {
            $matches = $this->base->matchAllOffsets();
            if (!$matches->hasGroup($nameOrIndex)) {
                throw new NonexistentGroupException($nameOrIndex);
            }
        }
    }

    private function getReplacementOrHandle(array $match, $nameOrIndex, GroupMapper $mapper, MatchRs $substitute): string
    {
        $occurrence = $this->occurrence($match, $nameOrIndex);
        $detail = new LazyDetailImpl($this->base, $this->counter, $this->limit);
        if ($occurrence === null) { // here "null" means group was not matched
            $replacement = $substitute->substituteGroup($detail);
            // here "null" means "no replacement provided, ignore me, use the full match"
            return $replacement ?? $match[0];
        }
        $mapper->useExceptionValues($occurrence, $nameOrIndex, $match[0]);
        return $mapper->map($occurrence, $detail) ?? $match[0];
    }

    private function occurrence(array $match, $nameOrIndex): ?string
    {
        if (array_key_exists($nameOrIndex, $match)) {
            return $this->makeSureOccurrence($nameOrIndex, $match[$nameOrIndex]);
        }
        return null;
    }

    private function makeSureOccurrence($nameOrIndex, string $occurrence): ?string
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
        if (!$matches->isGroupMatched($nameOrIndex, $this->counter)) {
            return null;
        }
        return $occurrence;
    }
}
