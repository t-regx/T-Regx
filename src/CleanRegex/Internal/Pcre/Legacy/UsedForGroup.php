<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Internal\Match\Details\MatchDetail;

/**
 * @deprecated
 */
interface UsedForGroup
{
    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     */
    public function isGroupMatched($nameOrIndex): bool;

    /**
     * @see MatchDetail::get
     * @see MatchedGroup
     * @see MatchDetail::group
     */
    public function getGroupTextAndOffset($nameOrIndex): array;
}
