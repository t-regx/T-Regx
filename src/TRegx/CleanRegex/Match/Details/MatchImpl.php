<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class MatchImpl implements Match
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

    public function __construct(Subjectable $subjectable,
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

    public function toInt(): int
    {
        $match = $this->match->getText();
        if (Integer::isValid($match)) {
            return $match;
        }
        throw IntegerFormatException::forMatch($match);
    }

    public function isInt(): bool
    {
        return Integer::isValid($this->match->getText());
    }

    /**
     * @param string|int $nameOrIndex
     * @return MatchGroup
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): MatchGroup
    {
        if (!$this->hasGroup($nameOrIndex)) {
            throw new NonexistentGroupException($nameOrIndex);
        }
        return $this->getGroupFacade($nameOrIndex)->createGroup($this->match);
    }

    private function getGroupFacade($nameOrIndex): GroupFacade
    {
        return new GroupFacade($this->match, $this->subjectable, $nameOrIndex, $this->strategy, $this->allFactory);
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
        return new IndexedGroups($this->match, $this->subjectable);
    }

    public function namedGroups(): NamedGroups
    {
        return new NamedGroups($this->match, $this->subjectable);
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        $this->validateGroupName($nameOrIndex);
        return $this->match->hasGroup($nameOrIndex);
    }

    private function validateGroupName($nameOrIndex): void
    {
        (new GroupNameValidator($nameOrIndex))->validate();
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
        return $this->getMatches()->getTexts();
    }

    private function getMatches(): IRawMatchesOffset
    {
        return $this->allFactory->getRawMatches();
    }

    public function offset(): int
    {
        return ByteOffset::toCharacterOffset($this->subjectable->getSubject(), $this->byteOffset());
    }

    public function byteOffset(): int
    {
        return $this->match->byteOffset();
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
