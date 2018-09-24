<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\SafeRegex\preg;
use function array_key_exists;

class GroupLimitAll
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
        if ($limit === -1) {
            return $matches[$this->nameOrIndex];
        }
        return array_slice($matches[$this->nameOrIndex], 0, $limit);
    }

    private function getMatches(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, $this->pregMatchFlags());
        return $matches;
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL;
        }
        return 0;
    }

    private function groupExistsIn(array $matches): bool
    {
        return array_key_exists($this->nameOrIndex, $matches);
    }
}
