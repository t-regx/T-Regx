<?php
namespace TRegx\CleanRegex\Internal\Model;

interface GroupHasAware
{
    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool;
}
