<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use CleanRegex\Internal\PatternDelimiterer;
use CleanRegex\Match\MatchPattern;
use CleanRegex\Replace\ReplacePattern;

class Pattern
{
    /** @var string */
    public $pattern;

    /** @var string */
    private $flags;

    public function __construct(string $pattern, string $flags = '')
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
    }

    public function match(string $subject): MatchPattern
    {
        return new MatchPattern(new InternalPattern($this->pattern), $subject);
    }

    public function matches(string $subject): bool
    {
        return (new MatchesPattern(new InternalPattern($this->pattern), $subject))->matches();
    }

    public function replace(string $subject): ReplacePattern
    {
        return new ReplacePattern(new InternalPattern($this->pattern), $subject);
    }

    public function filter(array $haystack): array
    {
        return (new FilterArrayPattern(new InternalPattern($this->pattern), $haystack))->filter();
    }

    public function split(string $subject): SplitPattern
    {
        return new SplitPattern(new InternalPattern($this->pattern), $subject);
    }

    public function count(string $subject): int
    {
        return (new CountPattern(new InternalPattern($this->pattern), $subject))->count();
    }

    public function quote(): string
    {
        return (new QuotePattern(new InternalPattern($this->pattern)))->quote();
    }

    public function valid(): bool
    {
        return (new ValidPattern(new InternalPattern($this->pattern)))->isValid();
    }

    public function delimitered(): ?string
    {
        return (new PatternDelimiterer())->delimiter($this->pattern);
    }
}
