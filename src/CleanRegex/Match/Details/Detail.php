<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Match\Details\Group\Element;
use TRegx\CleanRegex\Match\Details\Group\Group;

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
    public function group($nameOrIndex);

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
