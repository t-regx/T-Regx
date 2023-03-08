<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Groups;

use TRegx\CleanRegex\Internal\GroupKey\Signatures;
use TRegx\CleanRegex\Internal\Model\GroupKeys;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\Prime;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Group;

class PrimeDetailGroups
{
    /** @var int */
    private $index;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Prime */
    private $prime;
    /** @var DetailGroups */
    private $groups;

    public function __construct(Subject $subject, Signatures $signatures, int $index, MatchAllFactory $allFactory, GroupKeys $groupKeys, Prime $prime)
    {
        $this->index = $index;
        $this->allFactory = $allFactory;
        $this->prime = $prime;
        $this->groups = new DetailGroups($subject, $signatures, $allFactory, $groupKeys);
    }

    /**
     * @return Group[]
     * @phpstan-return array<int, Group>
     */
    public function indexedGroups(): array
    {
        return $this->groups(new IndexKey());
    }

    /**
     * @return Group[]
     * @phpstan-return array<string, Group>
     */
    public function namedGroups(): array
    {
        return $this->groups(new NameKey());
    }

    /**
     * @template T of int|string
     * @param GroupArrayKey<T> $key
     * @return Group[]
     * @phpstan-return array<T, Group>
     */
    private function groups(GroupArrayKey $key): array
    {
        if ($this->index === 0) {
            return $this->groups->groups($key, $this->prime->firstUsedForGroup());
        }
        $match = new RawMatchesToMatchAdapter($this->allFactory->getRawMatches(), $this->index);
        return $this->groups->groups($key, $match);
    }
}
