<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\DetailGroup;
use TRegx\CleanRegex\Internal\Match\Details\DetailGroups;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\NumericDetail;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Offset\SubjectCoordinates;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class MatchDetail implements Detail
{
    /** @var DetailScalars */
    private $scalars;
    /** @var UserData */
    private $userData;
    /** @var SubjectCoordinates */
    private $coordinates;
    /** @var DuplicateName */
    private $duplicateName;
    /** @var NumericDetail */
    private $numericDetail;
    /** @var DetailGroup */
    private $group;
    /** @var DetailGroups */
    private $groups;

    private function __construct(
        Subject              $subject,
        int                  $index,
        int                  $limit,
        GroupAware           $groupAware,
        Entry                $matchEntry,
        GroupEntries         $entries,
        UsedForGroup         $usedForGroup,
        MatchAllFactory      $allFactory,
        UserData             $userData,
        GroupFactoryStrategy $strategy,
        Signatures           $signatures)
    {
        $this->scalars = new DetailScalars($matchEntry, $index, $limit, $allFactory, $subject);
        $this->userData = $userData;
        $this->coordinates = new SubjectCoordinates($matchEntry, $subject);
        $this->duplicateName = new DuplicateName($groupAware, $usedForGroup, $matchEntry, $subject, $strategy, $allFactory, $signatures);
        $this->numericDetail = new NumericDetail($matchEntry);
        $this->group = new DetailGroup($groupAware, $matchEntry, $usedForGroup, $signatures, $strategy, $allFactory, $subject);
        $this->groups = new DetailGroups($groupAware, $entries, $subject);
    }

    public static function create(Subject         $subject, int $index, int $limit,
                                  IRawMatchOffset $match, MatchAllFactory $allFactory,
                                  UserData        $userData, GroupFactoryStrategy $strategy = null): MatchDetail
    {
        return new self($subject, $index, $limit, $match, $match, $match, $match, $allFactory, $userData,
            $strategy ?? new MatchGroupFactoryStrategy(),
            new PerformanceSignatures($match, $match));
    }

    public function subject(): string
    {
        return $this->scalars->subject();
    }

    public function index(): int
    {
        return $this->scalars->detailIndex();
    }

    public function limit(): int
    {
        return $this->scalars->detailsLimit();
    }

    public function text(): string
    {
        return $this->scalars->matchedText();
    }

    public function textLength(): int
    {
        return $this->coordinates->characterLength();
    }

    public function textByteLength(): int
    {
        return $this->coordinates->byteLength();
    }

    public function toInt(int $base = null): int
    {
        return $this->numericDetail->asInteger(new Base($base));
    }

    public function isInt(int $base = null): bool
    {
        return $this->numericDetail->isInteger(new Base($base));
    }

    public function get($nameOrIndex): string
    {
        return $this->group->text(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return Group
     */
    public function group($nameOrIndex): Group
    {
        return $this->group->group(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->group->exists(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function matched($nameOrIndex): bool
    {
        return $this->group->matched(GroupKey::of($nameOrIndex));
    }

    public function usingDuplicateName(): DuplicateName
    {
        return $this->duplicateName;
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return $this->groups->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->groups->groupsCount();
    }

    public function groups(): IndexedGroups
    {
        return $this->groups->indexedGroups();
    }

    public function namedGroups(): NamedGroups
    {
        return $this->groups->namedGroups();
    }

    public function all(): array
    {
        return $this->scalars->otherTexts();
    }

    public function offset(): int
    {
        return $this->coordinates->characterOffset();
    }

    public function tail(): int
    {
        return $this->coordinates->characterTail();
    }

    public function byteOffset(): int
    {
        return $this->coordinates->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->coordinates->byteTail();
    }

    public function setUserData($userData): void
    {
        $this->userData->set($this, $userData);
    }

    public function getUserData()
    {
        return $this->userData->get($this);
    }

    public function __toString(): string
    {
        return $this->text();
    }
}
