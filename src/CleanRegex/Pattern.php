<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\EntryPoints;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Filter;
use TRegx\CleanRegex\Internal\Needle;
use TRegx\CleanRegex\Internal\Splits;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\SubjectList;
use TRegx\CleanRegex\Match\Matcher;
use TRegx\CleanRegex\Match\Search;
use TRegx\CleanRegex\Replace\Replace;
use TRegx\SafeRegex\preg;

class Pattern
{
    public const MULTILINE = 'm';
    public const CASE_INSENSITIVE = 'i';
    public const UNICODE = 'u';
    public const DUPLICATE_NAMES = 'J';
    public const NO_AUTOCAPTURE = 'n';
    public const IGNORE_WHITESPACE = 'x';
    public const ANCHORED = 'A';
    public const SINGLELINE = 's';
    public const DOLLAR_ENDONLY = 'D';
    public const RESTRICTIVE_ESCAPE = 'X';
    public const GREEDYNESS_INVERTED = 'U';
    public const STUDY = 'S';

    use EntryPoints;

    /** @var Predefinition */
    private $predefinition;
    /** @var Needle */
    private $needle;
    /** @var Filter */
    private $filter;

    public function __construct(Expression $expression)
    {
        $this->predefinition = $expression->predefinition();
        $this->needle = new Needle($this->predefinition);
        $this->filter = new Filter($this->predefinition);
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

    public function match(string $subject): Matcher
    {
        return new Matcher($this->predefinition->definition(), new Subject($subject));
    }

    public function replace(string $subject): Replace
    {
        return new Replace($this->predefinition->definition(), new Subject($subject));
    }

    public function prune(string $subject): string
    {
        return preg::replace($this->predefinition->definition()->pattern, '', $subject);
    }

    /**
     * @param string[] $subjects
     * @return string[]
     */
    public function filter(array $subjects): array
    {
        return $this->filter->filtered(new SubjectList($subjects));
    }

    /**
     * @param string[] $subjects
     * @return string[]
     */
    public function reject(array $subjects): array
    {
        return $this->filter->rejected(new SubjectList($subjects));
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
