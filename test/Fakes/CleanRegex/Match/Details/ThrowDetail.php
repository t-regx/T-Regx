<?php
namespace Test\Fakes\CleanRegex\Match\Details;

use Test\Utils\Fails;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;

class ThrowDetail implements Detail
{
    use Fails;

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

    public function groupExists($nameOrIndex): bool
    {
        throw $this->fail();
    }

    public function text(): string
    {
        throw $this->fail();
    }

    public function length(): int
    {
        throw $this->fail();
    }

    public function byteLength(): int
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

    public function group($nameOrIndex): Group
    {
        throw $this->fail();
    }

    public function groups(): array
    {
        throw $this->fail();
    }

    public function namedGroups(): array
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

    public function __toString(): string
    {
        throw $this->fail();
    }
}
