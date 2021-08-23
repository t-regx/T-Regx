<?php
namespace Test\Utils\Impl;

use AssertionError;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DuplicateName;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class TextDetail implements Detail
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function text(): string
    {
        return $this->text;
    }

    private function fail(): AssertionError
    {
        return new AssertionError("Failed to assert Detail wasn't used");
    }

    public function subject(): string
    {
        throw $this->fail();
    }

    public function groupNames(): array
    {
        throw $this->fail();
    }

    public function groupsCount(): int
    {
        throw $this->fail();
    }

    public function hasGroup($nameOrIndex): bool
    {
        throw $this->fail();
    }

    public function textLength(): int
    {
        throw $this->fail();
    }

    public function textByteLength(): int
    {
        throw $this->fail();
    }

    public function toInt(int $base = null): int
    {
        throw $this->fail();
    }

    public function isInt(int $base = null): bool
    {
        throw $this->fail();
    }

    public function index(): int
    {
        throw $this->fail();
    }

    public function limit(): int
    {
        throw $this->fail();
    }

    public function get($nameOrIndex): string
    {
        throw $this->fail();
    }

    public function group($nameOrIndex)
    {
        throw $this->fail();
    }

    public function usingDuplicateName(): DuplicateName
    {
        throw $this->fail();
    }

    public function groups(): IndexedGroups
    {
        throw $this->fail();
    }

    public function namedGroups(): NamedGroups
    {
        throw $this->fail();
    }

    public function matched($nameOrIndex): bool
    {
        throw $this->fail();
    }

    public function all(): array
    {
        throw $this->fail();
    }

    public function offset(): int
    {
        throw $this->fail();
    }

    public function tail(): int
    {
        throw $this->fail();
    }

    public function byteOffset(): int
    {
        throw $this->fail();
    }

    public function byteTail(): int
    {
        throw $this->fail();
    }

    public function setUserData($userData): void
    {
        throw $this->fail();
    }

    public function getUserData()
    {
        throw $this->fail();
    }

    public function __toString(): string
    {
        throw $this->fail();
    }
}
