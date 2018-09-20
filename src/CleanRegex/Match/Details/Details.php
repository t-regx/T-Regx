<?php
namespace CleanRegex\Match\Details;

interface Details
{
    public function subject(): string;

    /**
     * @return string[]
     */
    public function groupNames(): array;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool;
}
