<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;
use TRegx\CleanRegex\Replace\Details\Group\ReplaceGroup;

interface Detail extends Structure, Intable
{
    public function text(): string;

    public function toInt(int $base = 10): int;

    public function isInt(int $base = 10): bool;

    public function index(): int;

    /**
     * @param string|int $nameOrIndex
     * @return string
     */
    public function get($nameOrIndex): string;

    /**
     * @param string|int $nameOrIndex
     * @return Group|ReplaceGroup
     */
    public function group($nameOrIndex);

    public function usingDuplicateName(): DuplicateName;

    public function groups(): IndexedGroups;

    public function namedGroups(): NamedGroups;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function matched($nameOrIndex): bool;

    public function offset(): int;

    public function tail(): int;

    public function length(): int;

    public function byteOffset(): int;

    public function byteTail(): int;

    public function byteLength(): int;

    /**
     * @return string[]
     */
    public function all(): array;

    public function __toString(): string;
}
