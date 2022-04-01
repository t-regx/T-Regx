<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream;

/**
 * @deprecated
 */
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
     * @see GroupMatch
     * @see GroupMatchFindFirst
     * @see DuplicateName::group
     * @see MatchDetail::group
     */
    public function getGroupTextAndOffset($nameOrIndex): array;
}
