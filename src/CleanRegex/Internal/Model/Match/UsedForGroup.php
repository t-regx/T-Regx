<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream;

interface UsedForGroup
{
    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     * @see MatchGroupIntStream::first()
     * @see MatchGroupIntStream::firstKey()
     * @see MatchGroupStream::all
     * @see GroupFacade which is called by everything that calls {@see getGroupTextAndOffset}
     */
    public function isGroupMatched($nameOrIndex): bool;

    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     * @see GroupLimit
     * @see GroupLimitFindFirst
     * @see DuplicateName::group
     * @see MatchDetail::group
     */
    public function getGroupTextAndOffset($nameOrIndex): array;
}
