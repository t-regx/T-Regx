<?php
namespace TRegx\CleanRegex\Match\Details;

interface Details
{
    public function subject(): string;

    /**
     * @return string[]
     */
    public function groupNames(): array;

    public function groupsCount(): int;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool;
}
