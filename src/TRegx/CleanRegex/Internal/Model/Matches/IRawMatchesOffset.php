<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\Match;

interface IRawMatchesOffset extends IRawMatches, IRawWithGroups
{
    /**
     * @param UserData $userData
     * @return Match[]
     */
    public function getMatchObjects(UserData $userData): array;

    public function getLimitedGroupOffsets($nameOrIndex, int $limit);

    public function getFirstMatchObject(UserData $userData): Match;

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

    public function isGroupMatched($nameOrIndex, int $index);

    public function getRawMatchOffset(int $index): RawMatchOffset;

    public function getRawMatch(int $index): RawMatch;

    public function filterMatchesByMatchObjects(Predicate $predicate, UserData $userData): array;
}
