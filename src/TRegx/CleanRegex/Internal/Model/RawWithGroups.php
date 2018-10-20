<?php
namespace TRegx\CleanRegex\Internal\Model;

interface RawWithGroups
{
    /**
     * @return (string|int)[]
     */
    public function getGroupKeys(): array;
}
