<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Internal\Model\IRawWithGroups;

interface IRawMatchOffset extends IRawMatch, IRawWithGroups
{
    public function byteOffset(): int;

    public function isGroupMatched($nameOrIndex): bool;

    public function getGroupTextAndOffset($nameOrIndex): array;

    /**
     * @return (string|null)[]
     */
    public function getGroupsTexts(): array;

    /**
     * @return (int|null)[]
     */
    public function getGroupsOffsets(): array;
}
