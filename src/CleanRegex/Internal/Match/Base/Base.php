<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

interface Base
{
    public function match(): RawMatch;

    public function matchOffset(): RawMatchOffset;

    public function matchAll(): RawMatches;

    public function matchAllOffsets(): RawMatchesOffset;
}
