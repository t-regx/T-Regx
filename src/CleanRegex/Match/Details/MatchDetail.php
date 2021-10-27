<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\DetailGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\NumericDetail;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Offset\SubjectCoordinates;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class MatchDetail implements Detail
{
    /** @var Subject */
    private $subject;
    /** @var int */
    private $index;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var UserData */
    private $userData;
    /** @var int */
    private $limit;
    /** @var GroupAware */
    private $groupAware;
    /** @var Entry */
    private $entry;
    /** @var SubjectCoordinates */
    private $coordinates;
    /** @var GroupNames */
    private $groupNames;
    /** @var DuplicateName */
    private $duplicateName;
    /** @var NumericDetail */
    private $numericDetail;
    /** @var DetailGroup */
    private $detailGroup;
    /** @var IndexedGroups */
    private $indexedGroups;
    /** @var NamedGroups */
    private $namedGroups;

    private function __construct(
        Subject               $subject,
        int                   $index,
        int                   $limit,
        GroupAware            $groupAware,
        Entry                 $matchEntry,
        UsedInCompositeGroups $usedInCompo,
        UsedForGroup          $usedForGroup,
        MatchAllFactory       $allFactory,
        UserData              $userData,
        GroupFactoryStrategy  $strategy,
        Signatures            $signatures)
    {
        $this->subject = $subject;
        $this->index = $index;
        $this->limit = $limit;
        $this->groupAware = $groupAware;
        $this->entry = $matchEntry;
        $this->allFactory = $allFactory;
        $this->userData = $userData;
        $this->coordinates = new SubjectCoordinates($matchEntry, $subject);
        $this->groupNames = new GroupNames($groupAware);
        $this->duplicateName = new DuplicateName($groupAware, $usedForGroup, $matchEntry, $subject, $strategy, $allFactory, $signatures);
        $this->numericDetail = new NumericDetail($matchEntry);
        $this->detailGroup = new DetailGroup($groupAware, $matchEntry, $usedForGroup, $signatures, $strategy, $allFactory, $subject);
        $this->indexedGroups = new IndexedGroups($groupAware, $usedInCompo, $subject);
        $this->namedGroups = new NamedGroups($groupAware, $usedInCompo, $subject);
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
        return $this->subject->getSubject();
    }

    public function index(): int
    {
        return $this->index;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function text(): string
    {
        return $this->entry->text();
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
        return $this->detailGroup->text(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return Group
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): Group
    {
        return $this->detailGroup->group(GroupKey::of($nameOrIndex));
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
        return $this->groupNames->groupNames();
    }

    public function groupsCount(): int
    {
        $indexedGroups = \array_filter($this->groupAware->getGroupKeys(), '\is_int');
        return \count($indexedGroups) - 1;
    }

    public function groups(): IndexedGroups
    {
        return $this->indexedGroups;
    }

    public function namedGroups(): NamedGroups
    {
        return $this->namedGroups;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->detailGroup->groupExists(GroupKey::of($nameOrIndex));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @throws NonexistentGroupException
     */
    public function matched($nameOrIndex): bool
    {
        return $this->detailGroup->matched(GroupKey::of($nameOrIndex));
    }

    public function all(): array
    {
        return \array_values($this->allFactory->getRawMatches()->getTexts());
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
