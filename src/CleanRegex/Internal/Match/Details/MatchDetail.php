<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\Groups\PrimeDetailGroups;
use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Offset\SubjectCoordinate;
use TRegx\CleanRegex\Internal\Pcre\Legacy;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Match\Group;

class MatchDetail implements Detail
{
    /** @var DetailScalars */
    private $scalars;
    /** @var SubjectCoordinate */
    private $coordinate;
    /** @var NumericDetail */
    private $numericDetail;
    /** @var DetailGroup */
    private $group;
    /** @var PrimeDetailGroups */
    private $groups;
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;

    public function __construct(
        Subject            $subject,
        int                $index,
        GroupAware         $groupAware,
        Entry              $matchEntry,
        UsedForGroup       $usedForGroup,
        MatchAllFactory    $allFactory,
        Signatures         $signatures,
        Legacy\Prime\Prime $prime)
    {
        $this->scalars = new DetailScalars($matchEntry, $index, $allFactory, $subject);
        $this->coordinate = new SubjectCoordinate($matchEntry, $subject);
        $this->numericDetail = new NumericDetail($matchEntry);
        $this->group = new DetailGroup($groupAware, $usedForGroup, $signatures, $allFactory, $subject);
        $this->groups = new PrimeDetailGroups($subject, $signatures, $index, $allFactory, $groupAware, $prime);
        $this->groupNames = new GroupNames($groupAware);
        $this->groupsCount = new GroupsCount($groupAware);
    }

    public function subject(): string
    {
        return $this->scalars->subject();
    }

    public function index(): int
    {
        return $this->scalars->detailIndex();
    }

    public function text(): string
    {
        return $this->scalars->matchedText();
    }

    /**
     * @deprecated
     */
    public function length(): int
    {
        return $this->coordinate->characterLength();
    }

    /**
     * @deprecated
     */
    public function byteLength(): int
    {
        return $this->coordinate->byteLength();
    }

    /**
     * @deprecated
     */
    public function toInt(int $base = 10): int
    {
        return $this->numericDetail->asInteger(new Base($base));
    }

    /**
     * @deprecated
     */
    public function isInt(int $base = 10): bool
    {
        return $this->numericDetail->isInteger(new Base($base));
    }

    /**
     * @param string|int $nameOrIndex
     * @return string
     * @deprecated
     */
    public function get($nameOrIndex): string
    {
        return $this->group->text(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return Group
     * @deprecated
     */
    public function group($nameOrIndex): Group
    {
        return $this->group->group(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function groupExists($nameOrIndex): bool
    {
        return $this->group->exists(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @deprecated
     */
    public function matched($nameOrIndex): bool
    {
        return $this->group->matched(GroupKey::of($nameOrIndex));
    }

    /**
     * @return (string|null)[]
     * @deprecated
     */
    public function groupNames(): array
    {
        return $this->groupNames->groupNames();
    }

    /**
     * @deprecated
     */
    public function groupsCount(): int
    {
        return $this->groupsCount->groupsCount();
    }

    /**
     * @return Group[]
     * @deprecated
     */
    public function groups(): array
    {
        return $this->groups->indexedGroups();
    }

    /**
     * @return Group[]
     * @deprecated
     */
    public function namedGroups(): array
    {
        return $this->groups->namedGroups();
    }

    /**
     * @return string[]
     * @deprecated
     */
    public function all(): array
    {
        return $this->scalars->otherTexts();
    }

    /**
     * @deprecated
     */
    public function offset(): int
    {
        return $this->coordinate->characterOffset();
    }

    /**
     * @deprecated
     */
    public function tail(): int
    {
        return $this->coordinate->characterTail();
    }

    /**
     * @deprecated
     */
    public function byteOffset(): int
    {
        return $this->coordinate->byteOffset();
    }

    /**
     * @deprecated
     */
    public function byteTail(): int
    {
        return $this->coordinate->byteTail();
    }

    public function __toString(): string
    {
        return $this->text();
    }
}
