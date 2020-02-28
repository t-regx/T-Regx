<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\LazyMatchImpl;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;
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
    /** @var ReplaceSubstitute */
    private $substitute;
    /** @var Base */
    private $base;
    /** @var int */
    private $counter = -1;

    public function __construct(Pattern $pattern, Subjectable $subject, int $limit, ReplaceSubstitute $substitute, Base $base)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
        $this->base = $base;
    }

    public function replaceOrFallback($nameOrIndex, GroupMapper $mapper, ReplaceSubstitute $substitute): string
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

    private function getReplacementOrHandle(array $match, $nameOrIndex, GroupMapper $mapper, ReplaceSubstitute $substitute): string
    {
        $occurrence = $this->occurrence($match, $nameOrIndex);
        if ($occurrence === null) {
            return $substitute->substituteGroup(new LazyMatchImpl($this->pattern, $this->subject, $this->counter, $this->limit, $this->base)) ?? $match[0];
        }
        $mapper->useExceptionValues($occurrence, $nameOrIndex, $match[0]);
        return $mapper->map($occurrence) ?? $match[0];
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
