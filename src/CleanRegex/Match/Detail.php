<?php
namespace TRegx\CleanRegex\Match;

interface Detail extends Structure, Element
{
    public function index(): int;

    /**
     * @param string|int $nameOrIndex
     * @return string
     */
    public function get($nameOrIndex): string;

    /**
     * @param string|int $nameOrIndex
     * @return Group
     */
    public function group($nameOrIndex): Group;

    /**
     * @return Group[]
     */
    public function groups(): array;

    /**
     * @return Group[]
     */
    public function namedGroups(): array;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function matched($nameOrIndex): bool;

    /**
     * @return string[]
     */
    public function all(): array;

    public function __toString(): string;
}
