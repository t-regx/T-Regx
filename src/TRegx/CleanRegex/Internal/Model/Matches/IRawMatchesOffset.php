<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Internal\Model\IRawWithGroups;

interface IRawMatchesOffset extends IRawMatches, IRawWithGroups
{
//    /**
//     * @return Match[]
//     */
//    public function getMatchObjects(): array;
//
//    public function getLimitedGroupOffsets($nameOrIndex, int $limit);
//
//    public function getText(int $index): string;
//
//    public function getFirstText(): string;
//
//    public function getFirstMatchObject(): Match;
//
    public function getOffset(int $index): int;

    public function getTextAndOffset(int $index): array;

    public function getGroupTextAndOffset($nameOrIndex, int $index): array;

    /**
     * @param int $index
     * @return (int|null)[]
     */
    public function getGroupsOffsets(int $index): array;

    /**
     * @param int $index
     * @return (string|null)[]
     */
    public function getGroupsTexts(int $index): array;
//
//    /**
//     * @param string|int $group
//     * @return (string|null)[]
//     */
//    public function getGroupTexts($group): array;

    public function isGroupMatched($nameOrIndex, int $index);

//    public function getRawMatchOffset(int $index);
//
//    public function getRawMatch(int $index): RawMatch;
//
//    public function filterMatchesByMatchObjects(Predicate $predicate): array;
}
