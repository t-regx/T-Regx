<?php
namespace TRegx\CleanRegex\Internal\Model;

interface IRawWithGroups
{
    /**
     * @return (string|int)[]
     */
    public function getGroupKeys(): array;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool;
}
