<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchGroupable;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatches;
use TRegx\CleanRegex\Internal\Subjectable;

interface Base extends Subjectable
{
    public function getPattern(): InternalPattern;

    public function match(): RawMatch;

    public function matchOffset(): RawMatchOffset;

    public function matchGroupable(): IRawMatchGroupable;

    public function matchAll(): RawMatches;

    public function matchAllOffsets(): IRawMatchesOffset;

    public function getUserData(): UserData;
}
