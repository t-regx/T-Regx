<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Grouper;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\SafeRegex\preg;
use function array_key_exists;
use function array_map;
use function array_slice;
use function defined;

class MatchOffsetLimitAll
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Pattern $pattern, string $subject, $nameOrIndex)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function getAllForGroup(int $limit, bool $allowNegative): array
    {
        $matches = $this->getMatches();
        if (!$this->groupExistsIn($matches)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$allowNegative && $limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return $this->mapToOffset($this->getLimitedMatches($limit, $matches));
    }

    private function getMatches(): array
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches, $this->pregMatchFlags());
        return $matches;
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE;
        }
        return PREG_OFFSET_CAPTURE;
    }

    private function groupExistsIn(array $matches): bool
    {
        return array_key_exists($this->nameOrIndex, $matches);
    }

    private function getLimitedMatches(int $limit, $matches)
    {
        $match = $matches[$this->nameOrIndex];
        if ($limit === -1) {
            return $match;
        }
        return array_slice($match, 0, $limit);
    }

    private function mapToOffset(array $matches): array
    {
        return array_map(function ($match) {
            return (new Grouper($match))->getOffset();
        }, $matches);
    }
}
