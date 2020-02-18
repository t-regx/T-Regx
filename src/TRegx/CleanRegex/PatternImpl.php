<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\ForArray\ForArrayPatternImpl;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Remove\RemoveLimit;
use TRegx\CleanRegex\Remove\RemovePattern;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePatternFactory;
use TRegx\CleanRegex\Replace\ReplaceLimit;
use TRegx\CleanRegex\Replace\ReplaceLimitImpl;
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
        return new ReplaceLimitImpl(function (int $limit) use ($subject) {
            return new ReplacePatternImpl(
                new SpecificReplacePatternImpl($this->pattern, $subject, $limit, new DefaultStrategy()),
                $this->pattern,
                $subject,
                $limit,
                new ReplacePatternFactory());
        });
    }

    public function remove(string $subject): RemoveLimit
    {
        return new RemoveLimit(function (int $limit) use ($subject) {
            return (new RemovePattern($this->pattern, $subject, $limit))->remove();
        });
    }

    public function forArray(array $haystack): ForArrayPatternImpl
    {
        return new ForArrayPatternImpl($this->pattern, $haystack, false);
    }

    public function split(string $subject): array
    {
        return (new SplitPattern($this->pattern, $subject))->split();
    }

    public function count(string $subject): int
    {
        return (new CountPattern($this->pattern, new Subject($subject)))->count();
    }

    public function valid(): bool
    {
        return (new ValidPattern($this->pattern->pattern))->isValid();
    }

    public function delimiter(): string
    {
        return $this->pattern->pattern;
    }
}
