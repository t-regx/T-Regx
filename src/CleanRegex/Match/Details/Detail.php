<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\Group\ReplaceGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

interface Detail extends BaseDetail, Intable
{
    public function text(): string;

    public function textLength(): int;

    public function textByteLength(): int;

    public function toInt(): int;

    public function isInt(): bool;

    public function index(): int;

    public function limit(): int;

    /**
     * @param string|int $nameOrIndex
     * @return string
     * @throws NonexistentGroupException
     */
    public function get($nameOrIndex): string;

    /**
     * @param string|int $nameOrIndex
     * @return Group|ReplaceGroup
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex);

    public function usingDuplicateName(): DuplicateName;

    public function groups(): IndexedGroups;

    public function namedGroups(): NamedGroups;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @throws NonexistentGroupException
     */
    public function matched($nameOrIndex): bool;

    /**
     * @return string[]
     */
    public function all(): array;

    public function offset(): int;

    public function tail(): int;

    public function byteOffset(): int;

    public function byteTail(): int;

    public function setUserData($userData): void;

    public function getUserData();

    public function __toString(): string;
}
