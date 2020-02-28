<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

interface Match extends Details
{
    public function text(): string;

    public function textLength(): int;

    public function toInt(): int;

    public function isInt(): bool;

    public function index(): int;

    public function limit(): int;

    /**
     * @param string|int $nameOrIndex
     * @return MatchGroup
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex);

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

    public function byteOffset(): int;

    public function setUserData($userData): void;

    public function getUserData();

    public function __toString(): string;
}
