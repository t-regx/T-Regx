<?php
namespace Test\Utils;

use Exception;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;
use TRegx\CleanRegex\Match\Details\Match;

class OffsetMatchImpl implements Match
{
    /** @var int */
    private $byteOffset;

    public function __construct(int $byteOffset)
    {
        $this->byteOffset = $byteOffset;
    }

    public function byteOffset(): int
    {
        return $this->byteOffset;
    }

    public function subject(): string
    {
        throw new Exception();
    }

    public function groupNames(): array
    {
        throw new Exception();
    }

    public function hasGroup($nameOrIndex): bool
    {
        throw new Exception();
    }

    public function text(): string
    {
        throw new Exception();
    }

    public function index(): int
    {
        throw new Exception();
    }

    public function limit(): int
    {
        throw new Exception();
    }

    public function group($nameOrIndex): MatchGroup
    {
        throw new Exception();
    }

    public function groups(): IndexedGroups
    {
        throw new Exception();
    }

    public function namedGroups(): NamedGroups
    {
        throw new Exception();
    }

    public function matched($nameOrIndex): bool
    {
        throw new Exception();
    }

    public function all(): array
    {
        throw new Exception();
    }

    public function offset(): int
    {
        throw new Exception();
    }

    public function setUserData($userData): void
    {
        throw new Exception();
    }

    public function getUserData()
    {
        throw new Exception();
    }

    public function __toString(): string
    {
        return '';
    }
}
