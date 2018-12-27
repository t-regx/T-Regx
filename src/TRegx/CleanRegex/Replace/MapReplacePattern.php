<?php
namespace TRegx\CleanRegex\Replace;

interface MapReplacePattern extends MapGroupReplacePattern
{
    /**
     * @param string|int $nameOrIndex
     * @return MapGroupReplacePattern
     * @throws \InvalidArgumentException
     */
    public function group($nameOrIndex): MapGroupReplacePattern;
}
