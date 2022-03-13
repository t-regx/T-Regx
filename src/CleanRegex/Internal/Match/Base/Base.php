<?php
namespace TRegx\CleanRegex\Internal\Match\Base;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatches;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

interface Base
{
    public function definition(): Definition;

    public function match(): RawMatch;

    public function matchOffset(): RawMatchOffset;

    public function matchAll(): RawMatches;

    public function matchAllOffsets(): RawMatchesOffset;

    public function getUserData(): UserData;
}
