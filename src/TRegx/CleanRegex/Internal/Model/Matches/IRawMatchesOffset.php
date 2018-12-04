<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Model\Factory\MatchObjectFactory;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\Match;

interface IRawMatchesOffset extends IRawMatches, IRawWithGroups
{
    /**
     * @param MatchObjectFactory $factory
     * @return Match[]
     */
    public function getMatchObjects(MatchObjectFactory $factory): array;

    public function getLimitedGroupOffsets($nameOrIndex, int $limit);

    public function getFirstMatchObject(MatchObjectFactory $factory): Match;

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

    public function filterMatchesByMatchObjects(Predicate $predicate, MatchObjectFactory $factory): array;
}
