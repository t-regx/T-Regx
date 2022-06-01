<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\GroupMatchFindFirst;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream;

/**
 * @deprecated
 */
interface UsedForGroup
{
    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     * @see MatchGroupStream::all
     * @see GroupsFacade which is called by everything that calls {@see getGroupTextAndOffset}
     */
    public function isGroupMatched($nameOrIndex): bool;

    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     * @see GroupMatch
     * @see GroupMatchFindFirst
     * @see MatchDetail::group
     */
    public function getGroupTextAndOffset($nameOrIndex): array;
}
