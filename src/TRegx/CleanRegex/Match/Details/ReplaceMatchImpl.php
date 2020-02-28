<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class ReplaceMatchImpl implements ReplaceMatch
{
    /** @var Match */
    private $match;
    /** @var int */
    private $offsetModification;
    /** @var string */
    private $subjectModification;

    public function __construct(Match $match, int $offsetModification, string $subjectModification)
    {
        $this->match = $match;
        $this->offsetModification = $offsetModification;
        $this->subjectModification = $subjectModification;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }

    public function modifiedSubject(): string
    {
        return $this->subjectModification;
    }

    /**
     * @param string|int $nameOrIndex
     * @return ReplaceMatchGroup
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): ReplaceMatchGroup
    {
        /** @var ReplaceMatchGroup $matchGroup */
        $matchGroup = $this->match->group($nameOrIndex);
        return $matchGroup;
    }

    public function subject(): string
    {
        return $this->match->subject();
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return $this->match->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->match->groupsCount();
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->match->hasGroup($nameOrIndex);
    }

    public function text(): string
    {
        return $this->match->text();
    }

    public function textLength(): int
    {
        return $this->match->textLength();
    }

    public function toInt(): int
    {
        return $this->match->toInt();
    }

    public function isInt(): bool
    {
        return $this->match->isInt();
    }

    public function index(): int
    {
        return $this->match->index();
    }

    public function limit(): int
    {
        return $this->match->limit();
    }

    public function groups(): IndexedGroups
    {
        return $this->match->groups();
    }

    public function namedGroups(): NamedGroups
    {
        return $this->match->namedGroups();
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @throws NonexistentGroupException
     */
    public function matched($nameOrIndex): bool
    {
        return $this->match->matched($nameOrIndex);
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->match->all();
    }

    public function offset(): int
    {
        return $this->match->offset();
    }

    public function byteOffset(): int
    {
        return $this->match->byteOffset();
    }

    public function setUserData($userData): void
    {
        $this->match->setUserData($userData);
    }

    public function getUserData()
    {
        return $this->match->getUserData();
    }

    public function __toString(): string
    {
        return $this->match->__toString();
    }
}
