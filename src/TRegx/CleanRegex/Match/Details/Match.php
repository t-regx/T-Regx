<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\GroupNameIndexAssign;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;
use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_values;
use function is_string;

class Match implements MatchInterface
{
    protected const WHOLE_MATCH = 0;

    /** @var Subjectable */
    protected $subjectable;
    /** @var int */
    protected $index;
    /** @var RawMatchesOffset */
    protected $matches;

    /** @var GroupNameIndexAssign */
    private $groupAssign;
    /** @var GroupFactoryStrategy */
    private $strategy;

    public function __construct(Subjectable $subjectable, int $index, RawMatchesOffset $matches, GroupFactoryStrategy $strategy = null)
    {
        $this->subjectable = $subjectable;
        $this->index = $index;
        $this->matches = $matches;
        $this->groupAssign = new GroupNameIndexAssign($matches);
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
        return $this->matches->getText($this->index);
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
        return $this->getGroupFacade($nameOrIndex)->createGroup($this->strategy);
    }

    private function getGroupFacade($nameOrIndex): GroupFacade
    {
        return new GroupFacade($this->matches, $this->subjectable, $nameOrIndex, $this->index);
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return array_values(array_filter($this->matches->getGroupKeys(), function ($key) {
            return is_string($key);
        }));
    }

    public function groups(): IndexedGroups
    {
        return new IndexedGroups($this->matches, $this->index);
    }

    public function namedGroups(): NamedGroups
    {
        return new NamedGroups($this->matches, $this->index);
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        $this->validateGroupName($nameOrIndex);
        return $this->matches->hasGroup($nameOrIndex);
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
        return $this->matches->getAll();
    }

    public function offset(): int
    {
        return ByteOffset::toCharacterOffset($this->subjectable->getSubject(), $this->byteOffset());
    }

    public function byteOffset(): int
    {
        return $this->matches->getOffset($this->index);
    }

    public function groupOffsets(): array
    {
        return $this->byteGroupOffsets();
    }

    public function byteGroupOffsets(): array
    {
        return $this->matches->getGroupsOffsets($this->index);
    }

    public function __toString(): string
    {
        return $this->text();
    }
}
