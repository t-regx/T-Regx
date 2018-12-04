<?php
namespace TRegx\CleanRegex\Internal\Model\Factory;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;

interface MatchObjectFactory
{
    public function create(int $index, IRawMatchOffset $matchOffset, MatchAllFactory $matchAllFactory);
}
