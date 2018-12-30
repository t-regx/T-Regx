<?php
namespace TRegx\CleanRegex\Replace\Map;

interface MapReplacePattern extends MapGroupReplacePattern
{
    /**
     * @param string|int $nameOrIndex
     * @return MapGroupReplacePattern
     * @throws \InvalidArgumentException
     */
    public function group($nameOrIndex): MapGroupReplacePattern;
}
