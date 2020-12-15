<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\ReplaceDetailGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class ReplaceMatchImpl implements ReplaceDetail, ReplaceMatch
{
    /** @var Detail */
    private $detail;
    /** @var int */
    private $offsetModification;
    /** @var string */
    private $subjectModification;

    public function __construct(Detail $detail, int $offsetModification, string $subjectModification)
    {
        $this->detail = $detail;
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

    public function get($nameOrIndex): string
    {
        return $this->detail->get($nameOrIndex);
    }

    /**
     * @param string|int $nameOrIndex
     * @return ReplaceDetailGroup
     */
    public function group($nameOrIndex): ReplaceDetailGroup
    {
        return $this->detail->group($nameOrIndex);
    }

    public function usingDuplicateName(): DuplicateName
    {
        return $this->detail->usingDuplicateName();
    }

    public function subject(): string
    {
        return $this->detail->subject();
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return $this->detail->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->detail->groupsCount();
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->detail->hasGroup($nameOrIndex);
    }

    public function text(): string
    {
        return $this->detail->text();
    }

    public function textLength(): int
    {
        return $this->detail->textLength();
    }

    public function textByteLength(): int
    {
        return $this->detail->textByteLength();
    }

    public function toInt(): int
    {
        return $this->detail->toInt();
    }

    public function isInt(): bool
    {
        return $this->detail->isInt();
    }

    public function index(): int
    {
        return $this->detail->index();
    }

    public function limit(): int
    {
        return $this->detail->limit();
    }

    public function groups(): IndexedGroups
    {
        return $this->detail->groups();
    }

    public function namedGroups(): NamedGroups
    {
        return $this->detail->namedGroups();
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @throws NonexistentGroupException
     */
    public function matched($nameOrIndex): bool
    {
        return $this->detail->matched($nameOrIndex);
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->detail->all();
    }

    public function offset(): int
    {
        return $this->detail->offset();
    }

    public function tail(): int
    {
        return $this->detail->tail();
    }

    public function byteOffset(): int
    {
        return $this->detail->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->detail->byteTail();
    }

    public function setUserData($userData): void
    {
        $this->detail->setUserData($userData);
    }

    public function getUserData()
    {
        return $this->detail->getUserData();
    }

    public function __toString(): string
    {
        return $this->detail->__toString();
    }
}
