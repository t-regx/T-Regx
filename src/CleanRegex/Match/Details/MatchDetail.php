<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\GroupHandle;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\UsedForGroup;
use TRegx\CleanRegex\Internal\Model\Match\UsedInCompositeGroups;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;
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
    /** @var GroupFactoryStrategy */
    private $strategy;
    /** @var UserData */
    private $userData;
    /** @var int */
    private $limit;
    /** @var GroupAware */
    private $groupAware;
    /** @var Entry */
    private $entry;
    /** @var UsedInCompositeGroups */
    private $usedInCompo;
    /** @var UsedForGroup */
    private $usedForGroup;
    /** @var Signatures */
    private $signatures;
    /** @var GroupHandle */
    private $groupHandle;
    /** @var GroupFacade */
    private $groupFacade;
    /** @var SubjectCoordinates */
    private $coordinates;

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
        $this->usedInCompo = $usedInCompo;
        $this->usedForGroup = $usedForGroup;
        $this->allFactory = $allFactory;
        $this->strategy = $strategy;
        $this->userData = $userData;
        $this->signatures = $signatures;
        $this->groupHandle = new FirstNamedGroup($this->signatures);
        $this->groupFacade = new GroupFacade($subject, $this->strategy, $this->allFactory,
            new NotMatched($this->groupAware, $subject),
            new FirstNamedGroup($this->signatures), $this->signatures);
        $this->coordinates = new SubjectCoordinates($matchEntry, $subject);
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
        $text = $this->entry->text();
        $number = new StringNumber($text);
        try {
            return $number->asInt(new Base($base));
        } catch (NumberFormatException $exception) {
            throw IntegerFormatException::forMatch($text, new Base($base));
        } catch (NumberOverflowException $exception) {
            throw IntegerOverflowException::forMatch($text, new Base($base));
        }
    }

    public function isInt(int $base = null): bool
    {
        $number = new StringNumber($this->entry->text());
        try {
            $number->asInt(new Base($base));
        } catch (NumberFormatException | NumberOverflowException $exception) {
            return false;
        }
        return true;
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
        $handle = $this->groupHandle->groupHandle($group);
        if ($this->usedForGroup->isGroupMatched($handle)) {
            [$text, $offset] = $this->usedForGroup->getGroupTextAndOffset($handle);
            return $text;
        }
        throw GroupNotMatchedException::forGet($group);
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
        return $this->groupFacade->createGroup($group, $this->usedForGroup, $this->entry);
    }

    public function usingDuplicateName(): DuplicateName
    {
        return new DuplicateName($this->groupAware, $this->usedForGroup, $this->entry, $this->subject, $this->strategy, $this->allFactory, $this->signatures);
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
        return new IndexedGroups($this->groupAware, $this->usedInCompo, $this->subject);
    }

    public function namedGroups(): NamedGroups
    {
        return new NamedGroups($this->groupAware, $this->usedInCompo, $this->subject);
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
