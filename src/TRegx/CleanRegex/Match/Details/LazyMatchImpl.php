<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class LazyMatchImpl implements Match
{
    /** @var Base */
    private $base;
    /** @var int */
    private $index;
    /** @var int */
    private $limit;

    /** @var Match|null */
    private $lazyMatch = null;

    public function __construct(Base $base, int $index, int $limit)
    {
        $this->base = $base;
        $this->index = $index;
        $this->limit = $limit;
    }

    private function match(): Match
    {
        $this->lazyMatch = $this->lazyMatch ?? $this->createLazyMatch();
        return $this->lazyMatch;
    }

    private function createLazyMatch(): Match
    {
        $matches = $this->base->matchAllOffsets();
        return new MatchImpl(
            $this->base,
            -99, // These values are never used  `index()` and `limit()` in LazyMatch aren't
            -99, // proxied to `Match()`, because they can be read from fields.
            new RawMatchesToMatchAdapter($matches, $this->index),
            new EagerMatchAllFactory($matches),
            new UserData()
        );
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

    public function byteOffset(): int
    {
        return $this->match()->byteOffset();
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
