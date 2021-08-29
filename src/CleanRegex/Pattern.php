<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\ForArray\ForArrayPattern;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\EntryPoints;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Internal\ValidPattern;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Replace\ReplaceLimit;
use TRegx\SafeRegex\preg;

class Pattern
{
    use EntryPoints;

    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function test(string $subject): bool
    {
        return preg::match($this->definition->pattern, $subject) === 1;
    }

    public function fails(string $subject): bool
    {
        return preg::match($this->definition->pattern, $subject) === 0;
    }

    public function match(string $subject): MatchPattern
    {
        return new MatchPattern($this->definition, new StringSubject($subject));
    }

    public function replace(string $subject): ReplaceLimit
    {
        return new ReplaceLimit($this->definition, new StringSubject($subject));
    }

    public function prune(string $subject): string
    {
        return preg::replace($this->definition->pattern, '', $subject);
    }

    public function forArray(array $haystack): ForArrayPattern
    {
        return new ForArrayPattern($this->definition, $haystack);
    }

    public function split(string $subject): array
    {
        return preg::split($this->definition->pattern, $subject, -1, \PREG_SPLIT_DELIM_CAPTURE);
    }

    public function count(string $subject): int
    {
        return preg::match_all($this->definition->pattern, $subject);
    }

    public function valid(): bool
    {
        return ValidPattern::isValid($this->definition->pattern);
    }

    public function delimited(): string
    {
        return $this->definition->pattern;
    }

    public function __toString(): string
    {
        return $this->definition->pattern;
    }
}
