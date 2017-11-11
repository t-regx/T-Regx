<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Internal\Pattern as InternalPattern;
use Danon\CleanRegex\Internal\PatternDelimiterer;
use Danon\CleanRegex\Match\MatchPattern;
use Danon\CleanRegex\Replace\ReplacePattern;

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

    public function matches(string $string): bool
    {
        return (new MatchesPattern(new InternalPattern($this->pattern), $string))->matches();
    }

    public function replace(string $string): ReplacePattern
    {
        return new ReplacePattern(new InternalPattern($this->pattern), $string);
    }

    public function filter(array $array): array
    {
        return (new FilterArrayPattern(new InternalPattern($this->pattern), $array))->filter();
    }

    public function split(string $string): SplitPattern
    {
        return new SplitPattern(new InternalPattern($this->pattern), $string);
    }

    public function count(string $string): int
    {
        return (new CountPattern(new InternalPattern($this->pattern), $string))->count();
    }

    public function quote(): string
    {
        return preg_quote($this->pattern);
    }

    public function valid(): bool
    {
        return (new ValidPattern(new InternalPattern($this)))->isValid();
    }

    public function delimitered(): ?string
    {
        return (new PatternDelimiterer())->delimiter($this->pattern);
    }
}
