<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\Offset\ByteOffset;
use TRegx\CleanRegex\Match\Details\Group\ReplaceGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class ReplaceDetail implements Detail
{
    /** @var Detail */
    private $detail;
    /** @var int */
    private $byteOffsetModification;
    /** @var string */
    private $subjectModification;

    public function __construct(Detail $detail, int $byteOffsetModification, string $subjectModification)
    {
        $this->detail = $detail;
        $this->byteOffsetModification = $byteOffsetModification;
        $this->subjectModification = $subjectModification;
    }

    public function modifiedSubject(): string
    {
        return $this->subjectModification;
    }

    public function modifiedOffset(): int
    {
        return ByteOffset::toCharacterOffset($this->modifiedSubject(), $this->byteModifiedOffset());
    }

    public function byteModifiedOffset(): int
    {
        return $this->byteOffset() + $this->byteOffsetModification;
    }

    public function get($nameOrIndex): string
    {
        return $this->detail->get($nameOrIndex);
    }

    /**
     * @param string|int $nameOrIndex
     * @return ReplaceGroup
     */
    public function group($nameOrIndex): ReplaceGroup
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

    public function toInt(int $base = null): int
    {
        return $this->detail->toInt($base);
    }

    public function isInt(int $base = null): bool
    {
        return $this->detail->isInt($base);
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
