<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

use TRegx\CleanRegex\Internal\Model\Entry;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

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
