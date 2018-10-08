<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Grouper;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Match\Groups\Strategy\GroupVerifier;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;
use TRegx\SafeRegex\preg;
use function array_key_exists;
use function defined;

class MatchOffsetLimitFirst
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupVerifier */
    private $groupVerifier;

    public function __construct(Pattern $pattern, string $subject, $nameOrIndex)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->nameOrIndex = $nameOrIndex;
        $this->groupVerifier = new MatchAllGroupVerifier();
    }

    public function getFirstForGroup(): int
    {
        $count = preg::match($this->pattern->pattern, $this->subject, $matches, $this->pregMatchFlags());
        if ($this->groupMatchedIn($matches)) {
            $group = $this->getGroup($matches);
            if ($group !== null) {
                return $group;
            }
        } else {
            $this->validateGroupExists();
            $this->validateSubjectMatched($count);
        }
        throw GroupNotMatchedException::forFirst($this->subject, $this->nameOrIndex);
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE;
        }
        return PREG_OFFSET_CAPTURE;
    }

    private function validateGroupExists(): void
    {
        if (!$this->groupExists()) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
    }

    private function groupMatchedIn(array $matches): bool
    {
        return array_key_exists($this->nameOrIndex, $matches);
    }

    private function getGroup(array $matches): ?int
    {
        return (new Grouper($matches[$this->nameOrIndex]))->getOffset();
    }

    private function groupExists(): bool
    {
        return $this->groupVerifier->groupExists($this->pattern->pattern, $this->nameOrIndex);
    }

    private function validateSubjectMatched(int $count): void
    {
        if ($count === 0) {
            throw SubjectNotMatchedException::forFirst($this->subject);
        }
    }
}
