<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Model\RawMatch;
use TRegx\CleanRegex\Internal\Model\RawMatches;
use TRegx\CleanRegex\Internal\Model\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\RawMatchGroupable;
use TRegx\CleanRegex\Internal\Model\RawMatchOffset;

interface Base extends Subjectable
{
    public function getPattern(): InternalPattern;

    public function match(): RawMatch;

    public function matchOffset(): RawMatchOffset;

    public function matchGroupable(): RawMatchGroupable;

    public function matchAll(): RawMatches;

    public function matchAllOffsets(): RawMatchesOffset;
}
