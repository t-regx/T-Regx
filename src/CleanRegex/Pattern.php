<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use CleanRegex\Match\MatchPattern;
use CleanRegex\Replace\ReplaceLimit;
use CleanRegex\Replace\ReplacePattern;

class Pattern
{
    /** @var InternalPattern */
    private $pattern;

    /** @var string */
    private $flags;

    public function __construct(string $pattern, string $flags = '')
    {
        $this->pattern = new InternalPattern($pattern, $flags);
        $this->flags = $flags;
    }

    public function match(string $subject): MatchPattern
    {
        return new MatchPattern($this->pattern, $subject);
    }

    public function matches(string $subject): bool
    {
        return (new MatchesPattern($this->pattern, $subject))->matches();
    }

    public function replace(string $subject): ReplaceLimit
    {
        return new ReplaceLimit(function (int $limit) use ($subject) {
            return new ReplacePattern($this->pattern, $subject, $limit);
        });
    }

    public function filter(array $haystack): array
    {
        return (new FilterArrayPattern($this->pattern, $haystack))->filter();
    }

    public function split(string $subject): SplitPattern
    {
        return new SplitPattern($this->pattern, $subject);
    }

    public function count(string $subject): int
    {
        return (new CountPattern($this->pattern, $subject))->count();
    }

    public function quote(): string
    {
        return (new QuotePattern($this->pattern))->quote();
    }

    public function valid(): bool
    {
        return (new ValidPattern($this->pattern))->isValid();
    }

    public function delimitered(): string
    {
        return $this->pattern->pattern;
    }

    public static function of(string $pattern, string $flags = ''): Pattern
    {
        return new Pattern($pattern, $flags);
    }

    public static function pattern(string $pattern, string $flags = ''): Pattern
    {
        return self::of($pattern, $flags);
    }
}
