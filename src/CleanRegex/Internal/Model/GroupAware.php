<?php
namespace TRegx\CleanRegex\Internal\Model;

interface GroupAware extends GroupHasAware
{
    public function getGroupKeys(): array;
}
