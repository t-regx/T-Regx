<?php
namespace TRegx\CleanRegex\Replace\Map;

interface ByReplacePattern extends ByGroupReplacePattern
{
    /**
     * @param string|int $nameOrIndex
     * @return ByGroupReplacePattern
     * @throws \InvalidArgumentException
     */
    public function group($nameOrIndex): ByGroupReplacePattern;
}
