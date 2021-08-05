<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class MatchDetail implements Detail
{
    /** @var Subjectable */
    private $subjectable;
    /** @var int */
    private $index;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var GroupFactoryStrategy */
    private $strategy;
    /** @var UserData */
    private $userData;
    /** @var int */
    private $limit;
    /** @var GroupAware */
    private $groupAware;
    /** @var MatchEntry */
    private $matchEntry;
    /** @var UsedInCompositeGroups */
    private $usedInCompo;
    /** @var UsedForGroup */
    private $usedForGroup;
    /** @var Signatures */
    private $signatures;

    private function __construct(
        Subjectable           $subjectable,
        int                   $index,
        int                   $limit,
        GroupAware            $groupAware,
        MatchEntry            $matchEntry,
        UsedInCompositeGroups $usedInCompo,
        UsedForGroup          $usedForGroup,
        MatchAllFactory       $allFactory,
        UserData              $userData,
        GroupFactoryStrategy  $strategy = null,
        Signatures            $signatures)
    {
        $this->subjectable = $subjectable;
        $this->index = $index;
        $this->limit = $limit;
        $this->groupAware = $groupAware;
        $this->matchEntry = $matchEntry;
        $this->usedInCompo = $usedInCompo;
        $this->usedForGroup = $usedForGroup;
        $this->allFactory = $allFactory;
        $this->strategy = $strategy ?? new MatchGroupFactoryStrategy();
        $this->userData = $userData;
        $this->signatures = $signatures;
    }

    public static function create(Subjectable     $subjectable, int $index, int $limit,
                                  IRawMatchOffset $match, MatchAllFactory $allFactory,
                                  UserData        $userData, GroupFactoryStrategy $strategy = null): MatchDetail
    {
        return new self($subjectable, $index, $limit, $match, $match, $match, $match, $allFactory, $userData, $strategy,
            new PerformanceSignatures($match, $match));
    }

    public function subject(): string
    {
        return $this->subjectable->getSubject();
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
        return $this->matchEntry->getText();
    }

    public function textLength(): int
    {
        return \mb_strlen($this->matchEntry->getText());
    }

    public function textByteLength(): int
    {
        return \strlen($this->matchEntry->getText());
    }

    public function toInt(): int
    {
        $text = $this->matchEntry->getText();
        if (Integer::isValid($text)) {
            return $text;
        }
        throw IntegerFormatException::forMatch($text);
    }

    public function isInt(): bool
    {
        return Integer::isValid($this->matchEntry->getText());
    }

    public function get($nameOrIndex): string
    {
        return $this->getGroup(GroupKey::of($nameOrIndex));
    }

    private function getGroup(GroupKey $group)
    {
        if (!$this->hasGroup($group->nameOrIndex())) {
            throw new NonexistentGroupException($group);
        }
        $nameAssign = new GroupNameIndexAssign($this->groupAware, $this->allFactory); // To handle J flag
        [$name, $index] = $nameAssign->getNameAndIndex($group);
        if ($this->usedForGroup->isGroupMatched($index)) {
            [$text, $offset] = $this->usedForGroup->getGroupTextAndOffset($index);
            return $text;
        }
        throw GroupNotMatchedException::forGet($this->subjectable, $group);
    }

    /**
     * @param string|int $nameOrIndex
     * @return Group
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): Group
    {
        return $this->groupBuilder(GroupKey::of($nameOrIndex));
    }

    private function groupBuilder(GroupKey $group): Group
    {
        if (!$this->hasGroup($group->nameOrIndex())) {
            throw new NonexistentGroupException($group);
        }
        return $this->getGroupFacade($group)->createGroup($this->usedForGroup, $this->matchEntry);
    }

    private function getGroupFacade(GroupKey $groupId): GroupFacade
    {
        return new GroupFacade($this->subjectable, $groupId, $this->strategy,
            $this->allFactory,
            new NotMatched($this->groupAware, $this->subjectable),
            new FirstNamedGroup($this->signatures), $this->signatures);
    }

    public function usingDuplicateName(): DuplicateName
    {
        return new DuplicateName($this->groupAware, $this->usedForGroup, $this->matchEntry, $this->subjectable, $this->strategy, $this->allFactory, $this->signatures);
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return (new GroupNames($this->groupAware))->groupNames();
    }

    public function groupsCount(): int
    {
        $indexedGroups = \array_filter($this->groupAware->getGroupKeys(), '\is_int');
        return \count($indexedGroups) - 1;
    }

    public function groups(): IndexedGroups
    {
        return new IndexedGroups($this->groupAware, $this->usedInCompo, $this->subjectable);
    }

    public function namedGroups(): NamedGroups
    {
        return new NamedGroups($this->groupAware, $this->usedInCompo, $this->subjectable);
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->groupAware->hasGroup(GroupKey::of($nameOrIndex)->nameOrIndex());
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @throws NonexistentGroupException
     */
    public function matched($nameOrIndex): bool
    {
        return $this->group($nameOrIndex)->matched();
    }

    public function all(): array
    {
        return \array_values($this->allFactory->getRawMatches()->getTexts());
    }

    public function offset(): int
    {
        return ByteOffset::toCharacterOffset($this->subjectable->getSubject(), $this->byteOffset());
    }

    public function tail(): int
    {
        return ByteOffset::toCharacterOffset($this->subjectable->getSubject(), $this->byteTail());
    }

    public function byteOffset(): int
    {
        return $this->matchEntry->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->matchEntry->byteOffset() + \strlen($this->matchEntry->getText());
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
