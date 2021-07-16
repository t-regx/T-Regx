<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Internal\Model\IRawWithGroups;

interface IRawMatchOffset extends IRawMatch, IRawWithGroups
{
    public function byteOffset(): int;

    public function isGroupMatched($nameOrIndex): bool;

    public function getGroupTextAndOffset($nameOrIndex): array;

    public function getGroupsTexts(): array;

    public function getGroupsOffsets(): array;
}
