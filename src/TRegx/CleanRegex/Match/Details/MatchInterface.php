<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;

interface MatchInterface extends Details
{
    public function text(): string;

    public function index(): int;

    /**
     * @param string|int $nameOrIndex
     * @return MatchGroup
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): MatchGroup;

    public function groups(): IndexedGroups;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     * @throws NonexistentGroupException
     */
    public function matched($nameOrIndex): bool;

    public function offset(): int;
}
