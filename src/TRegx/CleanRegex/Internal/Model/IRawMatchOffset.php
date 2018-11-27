<?php
namespace TRegx\CleanRegex\Internal\Model;

interface IRawMatchOffset extends IRawMatch, IRawWithGroups
{
    public function isGroupMatched($nameOrIndex): bool;

    public function getGroupTextAndOffset($nameOrIndex): array;
}
