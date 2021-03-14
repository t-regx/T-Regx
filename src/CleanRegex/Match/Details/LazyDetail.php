<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class LazyDetail implements Detail
{
    /** @var Base */
    private $base;
    /** @var int */
    private $index;
    /** @var int */
    private $limit;

    /** @var MatchDetail|null */
    private $lazyMatch = null;

    public function __construct(Base $base, int $index, int $limit)
    {
        $this->base = $base;
        $this->index = $index;
        $this->limit = $limit;
    }

    private function match(): MatchDetail
    {
        $this->lazyMatch = $this->lazyMatch ?? $this->createLazyMatch();
        return $this->lazyMatch;
    }

    private function createLazyMatch(): MatchDetail
    {
        $matches = $this->base->matchAllOffsets();
        return new MatchDetail(
            $this->base,
            -99, // These values are never used, because `index()` and `limit()` in LazyMatch aren't
            -99, // passed through `Detail`, because they are read from fields.
            // We could pass real data here, but we could never test it, since the code doesn't
            // use those values. We could also pass it and read it, but then LazyDetail.index()
            // and  LazyDetail.limit() would perform match unnecessarily.
            new RawMatchesToMatchAdapter($matches, $this->index),
            new EagerMatchAllFactory($matches),
            new UserData());
    }

    public function subject(): string
    {
        return $this->base->getSubject();
    }

    public function groupNames(): array
    {
        return $this->match()->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->match()->groupsCount();
    }

    public function hasGroup($nameOrIndex): bool
    {
        return $this->match()->hasGroup($nameOrIndex);
    }

    public function text(): string
    {
        return $this->match()->text();
    }

    public function textLength(): int
    {
        return $this->match()->textLength();
    }

    public function textByteLength(): int
    {
        return $this->match()->textByteLength();
    }

    public function toInt(): int
    {
        return $this->match()->toInt();
    }

    public function isInt(): bool
    {
        return $this->match()->isInt();
    }

    public function index(): int
    {
        return $this->index;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function get($nameOrIndex): string
    {
        return $this->match()->get($nameOrIndex);
    }

    public function group($nameOrIndex)
    {
        return $this->match()->group($nameOrIndex);
    }

    public function usingDuplicateName(): DuplicateName
    {
        return $this->match()->usingDuplicateName();
    }

    public function groups(): IndexedGroups
    {
        return $this->match()->groups();
    }

    public function namedGroups(): NamedGroups
    {
        return $this->match()->namedGroups();
    }

    public function matched($nameOrIndex): bool
    {
        return $this->match()->matched($nameOrIndex);
    }

    public function all(): array
    {
        return $this->match()->all();
    }

    public function offset(): int
    {
        return $this->match()->offset();
    }

    public function tail(): int
    {
        return $this->match()->tail();
    }

    public function byteOffset(): int
    {
        return $this->match()->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->match()->byteTail();
    }

    public function setUserData($userData): void
    {
        $this->match()->setUserData($userData);
    }

    public function getUserData()
    {
        return $this->match()->getUserData();
    }

    public function __toString(): string
    {
        return $this->match();
    }
}
