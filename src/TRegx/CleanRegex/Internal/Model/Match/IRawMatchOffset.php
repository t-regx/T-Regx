<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface IRawMatchOffset extends IRawMatch, IRawWithGroups
{
    public function isGroupMatched($nameOrIndex): bool;

    public function getGroupTextAndOffset($nameOrIndex): array;

    public function byteOffset(): int;

    /**
     * @return (string|null)[]
     */
    public function getGroupsTexts(): array;

    /**
     * @return (int|null)[]
     */
    public function getGroupsOffsets(): array;
}
