<?php
namespace TRegx\CleanRegex\Internal\Model\Matches;

use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;
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
     * This method is only for performance (to just return what's already there). This whole class idea,
     * is to make access to matches uniform, but making application slower just for the sake of internal
     * uniformity would be dumb.
     * If faster way is found to create MatchGroup[], then this method should be removed.
     * The methods:
     *  - getGroupTextAndOffset($a, $b);
     *  - getGroupTextAndOffsetAll($a)[$b];
     * should have identical results
     */
    public function getGroupTextAndOffsetAll($nameOrIndex): array;

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

    public function getIndexedRawMatchOffset(int $index);

    public function getRawMatchOffset(int $index): RawMatchOffset;

    public function getRawMatch(int $index): RawMatch;

    public function filterMatchesByMatchObjects(Predicate $predicate, MatchObjectFactory $factory): array;
}
