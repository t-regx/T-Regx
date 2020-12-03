<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Internal\Replace\NonReplaced\MatchRs;
use TRegx\CleanRegex\Match\Details\Detail;

/**
 * This interface exists to allow {@see Wrapper} hierarchy,
 * to map arbitrary, compatible, multiple hierarchies, like
 * {@see GroupMapper} hierarchy or {@see MatchRs} hierarchy.
 *
 * When PHP finally gets generics, we should make the argument
 * generic <T>, instead of {@see Detail}
 */
interface Wrappable
{
    public function apply(Detail $detail): ?string;
}
