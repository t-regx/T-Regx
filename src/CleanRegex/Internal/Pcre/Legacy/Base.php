<?php
namespace TRegx\CleanRegex\Internal\Pcre\Legacy;

/**
 * @deprecated
 */
interface Base
{
    public function match(): RawMatch;

    public function matchOffset(): RawMatchOffset;

    public function matchAll(): RawMatches;

    public function matchAllOffsets(): RawMatchesOffset;
}
