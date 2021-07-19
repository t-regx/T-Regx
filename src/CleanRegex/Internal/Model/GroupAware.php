<?php
namespace TRegx\CleanRegex\Internal\Model;

interface GroupAware
{
    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool;

    public function getGroupKeys(): array;
}
