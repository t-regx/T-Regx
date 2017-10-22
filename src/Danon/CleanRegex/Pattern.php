<?php
namespace Danon\CleanRegex;

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

    public function match(string $string): MatchPattern
    {
        return new MatchPattern($this, $string);
    }

    public function matches(string $string): bool
    {
        return (new MatchesPattern($this, $string))->matches();
    }

    public function replace(string $string): ReplacePattern
    {
        return new ReplacePattern($this, $string);
    }

    public function filter(array $array): array
    {
        return (new FilterArrayPattern($this, $array))->filter();
    }

    public function split(string $string): SplitPattern
    {
        return new SplitPattern($this, $string);
    }

    public function count(string $string): int
    {
        return (new CountPattern($this, $string))->count();
    }

    public function quote(): string
    {
        return preg_quote($this->pattern);
    }

    public function valid(): bool
    {
        return (new ValidPattern($this))->isValid();
    }
}
