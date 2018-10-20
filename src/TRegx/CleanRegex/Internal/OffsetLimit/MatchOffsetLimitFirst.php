<?php
namespace TRegx\CleanRegex\Internal\OffsetLimit;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Grouper;
use TRegx\CleanRegex\Internal\Match\Adapter\Base;
use TRegx\CleanRegex\Match\Groups\Strategy\GroupVerifier;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;
use function array_key_exists;

class MatchOffsetLimitFirst
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupVerifier */
    private $groupVerifier;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->groupVerifier = new MatchAllGroupVerifier();
    }

    public function getFirstForGroup(): int
    {
        list($matches, $count) = $this->base->matchCountOffset();
        if ($this->groupMatchedIn($matches)) {
            $group = $this->getGroup($matches);
            if ($group !== null) {
                return $group;
            }
        } else {
            $this->validateGroupExists();
            $this->validateSubjectMatched($count);
        }
        throw GroupNotMatchedException::forFirst($this->base->getSubject(), $this->nameOrIndex);
    }

    private function groupMatchedIn(array $matches): bool
    {
        return array_key_exists($this->nameOrIndex, $matches);
    }

    private function getGroup(array $matches): ?int
    {
        return (new Grouper($matches[$this->nameOrIndex]))->getOffset();
    }

    private function validateGroupExists(): void
    {
        if (!$this->groupExists()) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
    }

    private function groupExists(): bool
    {
        return $this->groupVerifier->groupExists($this->base->getPattern()->pattern, $this->nameOrIndex);
    }

    private function validateSubjectMatched(int $count): void
    {
        if ($count === 0) {
            throw SubjectNotMatchedException::forFirst($this->base->getSubject());
        }
    }
}
