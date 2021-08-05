<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
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
    /** @var IRawMatchOffset */
    private $match;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var GroupFactoryStrategy */
    private $strategy;
    /** @var UserData */
    private $userData;
    /** @var int */
    private $limit;

    public function __construct(
        Subjectable $subjectable,
        int $index,
        int $limit,
        IRawMatchOffset $match,
        MatchAllFactory $allFactory,
        UserData $userData,
        GroupFactoryStrategy $strategy = null)
    {
        $this->subjectable = $subjectable;
        $this->index = $index;
        $this->limit = $limit;
        $this->match = $match;
        $this->allFactory = $allFactory;
        $this->strategy = $strategy ?? new MatchGroupFactoryStrategy();
        $this->userData = $userData;
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
        return $this->match->getText();
    }

    public function textLength(): int
    {
        return \mb_strlen($this->match->getText());
    }

    public function textByteLength(): int
    {
        return \strlen($this->match->getText());
    }

    public function toInt(): int
    {
        $text = $this->match->getText();
        if (Integer::isValid($text)) {
            return $text;
        }
        throw IntegerFormatException::forMatch($text);
    }

    public function isInt(): bool
    {
        return Integer::isValid($this->match->getText());
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
        $nameAssign = new GroupNameIndexAssign($this->match, $this->allFactory); // To handle J flag
        [$name, $index] = $nameAssign->getNameAndIndex($group);
        if ($this->match->isGroupMatched($index)) {
            [$text, $offset] = $this->match->getGroupTextAndOffset($index);
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
        return $this->getGroupFacade($group)->createGroup($this->match);
    }

    private function getGroupFacade(GroupKey $groupId): GroupFacade
    {
        return new GroupFacade($this->match, $this->subjectable, $groupId, $this->strategy, $this->allFactory, new FirstNamedGroup(new PerformanceSignatures($this->match, $this->match)));
    }

    public function usingDuplicateName(): DuplicateName
    {
        return new DuplicateName($this->match, $this->match, $this->subjectable, $this->strategy, $this->allFactory);
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return (new GroupNames($this->match))->groupNames();
    }

    public function groupsCount(): int
    {
        $indexedGroups = \array_filter($this->getMatches()->getGroupKeys(), '\is_int');
        return \count($indexedGroups) - 1;
    }

    public function groups(): IndexedGroups
    {
        return new IndexedGroups($this->match, $this->match, $this->subjectable);
    }

    public function namedGroups(): NamedGroups
    {
        return new NamedGroups($this->match, $this->match, $this->subjectable);
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        return $this->match->hasGroup(GroupKey::of($nameOrIndex)->nameOrIndex());
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
        return \array_values($this->getMatches()->getTexts());
    }

    private function getMatches(): RawMatchesOffset
    {
        return $this->allFactory->getRawMatches();
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
        return $this->match->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->match->byteOffset() + \strlen($this->match->getText());
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
