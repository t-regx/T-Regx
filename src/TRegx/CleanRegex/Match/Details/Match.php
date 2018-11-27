<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;
use function array_filter;
use function array_values;

class Match implements MatchInterface
{
    protected const WHOLE_MATCH = 0;

    /** @var Subjectable */
    protected $subjectable;
    /** @var int */
    protected $index;
    /** @var IRawMatchOffset */
    protected $match;

    /** @var GroupNameIndexAssign */
    private $groupAssign;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var GroupFactoryStrategy */
    private $strategy;
    /** @var mixed */
    private $userData;

    public function __construct(Subjectable $subjectable, int $index, IRawMatchOffset $match, MatchAllFactory $allFactory, GroupFactoryStrategy $strategy = null)
    {
        $this->subjectable = $subjectable;
        $this->index = $index;
        $this->match = $match;
        $this->groupAssign = new GroupNameIndexAssign($match, $allFactory);
        $this->allFactory = $allFactory;
        $this->strategy = $strategy ?? new MatchGroupFactoryStrategy();
    }

    public function subject(): string
    {
        return $this->subjectable->getSubject();
    }

    public function index(): int
    {
        return $this->index;
    }

    public function text(): string
    {
        return $this->match->getMatch();
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
        return $this->getGroupFacade($nameOrIndex)->createGroup();
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
        return array_values(array_filter($this->getMatches()->getGroupKeys(), '\is_string'));
    }

    public function groups(): IndexedGroups
    {
        return new IndexedGroups($this->match);
    }

    public function namedGroups(): NamedGroups
    {
        return new NamedGroups($this->match);
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
        return $this->group($nameOrIndex)->matches();
    }

    public function all(): array
    {
        return $this->getFirstFromAllMatches();
    }

    protected function getFirstFromAllMatches(): array
    {
        return $this->getMatches()->getGroupTexts(self::WHOLE_MATCH);
    }

    public function offset(): int
    {
        return ByteOffset::toCharacterOffset($this->subjectable->getSubject(), $this->byteOffset());
    }

    public function byteOffset(): int
    {
        return $this->match->byteOffset();
    }

    public function groupOffsets(): array
    {
        return $this->byteGroupOffsets();
    }

    public function byteGroupOffsets(): array
    {
        return $this->match->getGroupsOffsets();
    }

    public function setUserData($userData): void
    {
        $this->userData = $userData;
    }

    public function getUserData()
    {
        return $this->userData;
    }

    public function __toString(): string
    {
        return $this->text();
    }

    private function getMatches(): IRawMatchesOffset
    {
        return $this->allFactory->getRawMatches();
    }
}
