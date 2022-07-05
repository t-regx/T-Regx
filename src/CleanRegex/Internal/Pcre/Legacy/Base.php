<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

/**
 * @deprecated
 */
interface Base
{
    public function matchOffset(): RawMatchOffset;

    public function matchAll(): RawMatches;

    public function matchAllOffsets(): RawMatchesOffset;
}
