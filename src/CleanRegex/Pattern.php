<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\ForArray\ForArrayPattern;
use TRegx\CleanRegex\Internal\EntryPoints;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Needle;
use TRegx\CleanRegex\Internal\Splits;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Match\Search;
use TRegx\CleanRegex\Replace\ReplaceLimit;
use TRegx\SafeRegex\preg;

class Pattern
{
    use EntryPoints;

    /** @var Predefinition */
    private $predefinition;
    /** @var Needle */
    private $needle;

    public function __construct(Expression $expression)
    {
        $this->predefinition = $expression->predefinition();
        $this->needle = new Needle($this->predefinition);
    }

    public function test(string $subject): bool
    {
        return preg::match($this->predefinition->definition()->pattern, $subject) === 1;
    }

    public function fails(string $subject): bool
    {
        return preg::match($this->predefinition->definition()->pattern, $subject) === 0;
    }

    public function search(string $subject): Search
    {
        return new Search($this->predefinition->definition(), new Subject($subject));
    }

    public function match(string $subject): MatchPattern
    {
        return new MatchPattern($this->predefinition->definition(), new Subject($subject));
    }

    public function replace(string $subject): ReplaceLimit
    {
        return new ReplaceLimit($this->predefinition->definition(), new Subject($subject));
    }

    public function prune(string $subject): string
    {
        return preg::replace($this->predefinition->definition()->pattern, '', $subject);
    }

    public function forArray(array $haystack): ForArrayPattern
    {
        return new ForArrayPattern($this->predefinition->definition(), $haystack);
    }

    /**
     * @return string[]
     */
    public function cut(string $subject): array
    {
        return $this->needle->twoPieces($subject);
    }

    /**
     * @return string[]
     */
    public function split(string $subject): array
    {
        return $this->needle->splitAll($subject);
    }

    /**
     * @return string[]
     */
    public function splitStart(string $subject, int $maxSplits): array
    {
        return $this->needle->splitFromStart($subject, new Splits($maxSplits));
    }

    /**
     * @return string[]
     */
    public function splitEnd(string $subject, int $maxSplits): array
    {
        return $this->needle->splitFromEnd($subject, new Splits($maxSplits));
    }

    public function count(string $subject): int
    {
        return preg::match_all($this->predefinition->definition()->pattern, $subject);
    }

    public function valid(): bool
    {
        return $this->predefinition->valid();
    }

    public function delimited(): string
    {
        return $this->predefinition->definition()->pattern;
    }

    public function __toString(): string
    {
        return $this->predefinition->definition()->pattern;
    }
}
