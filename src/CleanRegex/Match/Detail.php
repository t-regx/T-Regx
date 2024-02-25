<?php
namespace TRegx\CleanRegex\Match;

interface Detail extends Structure, Element
{
    public function index(): int;

    /**
     * @param string|int $nameOrIndex
     * @return string
     * @deprecated
     */
    public function get($nameOrIndex): string;

    /**
     * @param string|int $nameOrIndex
     * @return Group
     * @deprecated
     */
    public function group($nameOrIndex): Group;

    /**
     * @return Group[]
     * @deprecated
     */
    public function groups(): array;

    /**
     * @return Group[]
     * @deprecated
     */
    public function namedGroups(): array;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @deprecated
     */
    public function matched($nameOrIndex): bool;

    /**
     * @return string[]
     * @deprecated
     */
    public function all(): array;

    public function __toString(): string;
}
