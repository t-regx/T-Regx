<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Internal\Model\GroupAware;

/**
 * @deprecated
 */
interface IRawMatchOffset extends
    GroupAware,
    Entry,
    GroupEntries,
    UsedForGroup
{
}
