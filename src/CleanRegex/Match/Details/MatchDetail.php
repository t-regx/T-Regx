<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Match\Details\DetailGroup;
use TRegx\CleanRegex\Internal\Match\Details\DetailGroups;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\NumericDetail;
use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Offset\SubjectCoordinate;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\UsedForGroup;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class MatchDetail implements Detail
{
    /** @var DetailScalars */
    private $scalars;
    /** @var SubjectCoordinate */
    private $coordinate;
    /** @var DuplicateName */
    private $duplicateName;
    /** @var NumericDetail */
    private $numericDetail;
    /** @var DetailGroup */
    private $group;
    /** @var DetailGroups */
    private $groups;

    public function __construct(
        Subject              $subject,
        int                  $index,
        GroupAware           $groupAware,
        Entry                $matchEntry,
        GroupEntries         $entries,
        UsedForGroup         $usedForGroup,
        MatchAllFactory      $allFactory,
        GroupFactoryStrategy $strategy,
        Signatures           $signatures)
    {
        $this->scalars = new DetailScalars($matchEntry, $index, $allFactory, $subject);
        $this->coordinate = new SubjectCoordinate($matchEntry, $subject);
        $this->duplicateName = new DuplicateName($groupAware, $usedForGroup, $matchEntry, $subject, $strategy, $allFactory, $signatures);
        $this->numericDetail = new NumericDetail($matchEntry);
        $this->group = new DetailGroup($groupAware, $matchEntry, $usedForGroup, $signatures, $strategy, $allFactory, $subject);
        $this->groups = new DetailGroups($groupAware, $entries, $subject);
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

    public function length(): int
    {
        return $this->coordinate->characterLength();
    }

    public function byteLength(): int
    {
        return $this->coordinate->byteLength();
    }

    public function toInt(int $base = 10): int
    {
        return $this->numericDetail->asInteger(new Base($base));
    }

    public function isInt(int $base = 10): bool
    {
        return $this->numericDetail->isInteger(new Base($base));
    }

    /**
     * @param string|int $nameOrIndex
     * @return string
     */
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
    public function groupExists($nameOrIndex): bool
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

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->scalars->otherTexts();
    }

    public function offset(): int
    {
        return $this->coordinate->characterOffset();
    }

    public function tail(): int
    {
        return $this->coordinate->characterTail();
    }

    public function byteOffset(): int
    {
        return $this->coordinate->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->coordinate->byteTail();
    }

    public function __toString(): string
    {
        return $this->text();
    }
}
