<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\ForArray\ForArrayPattern;
use TRegx\CleanRegex\ForArray\ForArrayPatternImpl;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\ValidPattern;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Remove\RemoveLimit;
use TRegx\CleanRegex\Remove\RemovePattern;
use TRegx\CleanRegex\Replace\ReplaceLimit;
use TRegx\CleanRegex\Replace\ReplacePatternImpl;
use TRegx\CleanRegex\Replace\SpecificReplacePatternImpl;
use TRegx\SafeRegex\preg;

class PatternImpl implements PatternInterface
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function test(string $subject): bool
    {
        return preg::match($this->pattern->pattern, $subject) === 1;
    }

    public function fails(string $subject): bool
    {
        return preg::match($this->pattern->pattern, $subject) === 0;
    }

    public function match(string $subject): MatchPattern
    {
        return new MatchPattern($this->pattern, $subject);
    }

    public function replace(string $subject): ReplaceLimit
    {
        return new ReplaceLimit(function (int $limit) use ($subject) {
            return new ReplacePatternImpl(
                new SpecificReplacePatternImpl($this->pattern, $subject, $limit, new DefaultStrategy(), new IgnoreCounting()), $this->pattern, $subject, $limit);
        });
    }

    public function remove(string $subject): RemoveLimit
    {
        return new RemoveLimit(function (int $limit) use ($subject) {
            return (new RemovePattern($this->pattern, $subject, $limit))->remove();
        });
    }

    public function forArray(array $haystack): ForArrayPattern
    {
        return new ForArrayPatternImpl($this->pattern, $haystack, false);
    }

    public function split(string $subject): array
    {
        return preg::split($this->pattern->pattern, $subject, -1, \PREG_SPLIT_DELIM_CAPTURE);
    }

    public function count(string $subject): int
    {
        return preg::match_all($this->pattern->pattern, $subject);
    }

    public function valid(): bool
    {
        return ValidPattern::isValid($this->pattern->pattern);
    }

    public function delimited(): string
    {
        return $this->pattern->pattern;
    }
}
