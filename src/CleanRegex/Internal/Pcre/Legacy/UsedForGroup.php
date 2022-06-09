<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

/**
 * @deprecated
 */
interface UsedForGroup
{
    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     * @see GroupsFacade which is called by everything that calls {@see getGroupTextAndOffset}
     */
    public function isGroupMatched($nameOrIndex): bool;

    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     * @see MatchDetail::group
     */
    public function getGroupTextAndOffset($nameOrIndex): array;
}
