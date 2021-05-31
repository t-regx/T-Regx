<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

interface Consumer
{
    public function condition(Feed $feed): Condition;

    public function consume(Feed $feed, EntitySequence $entities): void;
}
