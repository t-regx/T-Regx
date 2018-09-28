<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

interface MatchInterface extends Details
{
    public function match(): string;

    public function index(): int;

    /**
     * @param string|int $nameOrIndex
     * @return MatchGroup
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): MatchGroup;

    /**
     * @return string[]
     */
    public function namedGroups(): array;

    /**
     * @return string[]
     */
    public function groups(): array;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @throws NonexistentGroupException
     */
    public function matched($nameOrIndex): bool;

    public function offset(): int;
}
