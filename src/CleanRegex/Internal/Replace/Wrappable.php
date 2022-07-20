<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\MatchRs;
use TRegx\CleanRegex\Match\Detail;

/**
 * This interface exists to allow {@see Wrapper} hierarchy,
 * to map arbitrary, compatible, multiple hierarchies, like
 * {@see GroupMapper} hierarchy or {@see MatchRs} hierarchy.
 *
 * When PHP finally gets generics, we should make the argument
 * generic <T>, instead of {@see \TRegx\CleanRegex\Match\Detail}
 */
interface Wrappable
{
    public function apply(Detail $detail): ?string;
}
