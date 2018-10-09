<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Remove\RemoveLimit;
use TRegx\CleanRegex\Remove\RemovePattern;
use TRegx\CleanRegex\Replace\ReplaceLimit;
use TRegx\CleanRegex\Replace\ReplacePattern;

class Pattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(string $pattern, string $flags = '')
    {
        $this->pattern = new InternalPattern($pattern, $flags);
    }

    public function matches(string $subject): bool
    {
        return (new MatchesPattern($this->pattern, $subject))->matches();
    }

    public function fails(string $subject): bool
    {
        return (new MatchesPattern($this->pattern, $subject))->fails();
    }

    public function match(string $subject): MatchPattern
    {
        return new MatchPattern($this->pattern, $subject);
    }

    public function replace(string $subject): ReplaceLimit
    {
        return new ReplaceLimit(function (int $limit) use ($subject) {
            return new ReplacePattern($this->pattern, $subject, $limit);
        });
    }

    public function remove(string $subject): RemoveLimit
    {
        return new RemoveLimit(function (int $limit) use ($subject) {
            return (new RemovePattern($this->pattern, $subject, $limit))->remove();
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

    public function is(): IsPattern
    {
        return new IsPattern($this->pattern);
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
