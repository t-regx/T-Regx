<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer;

use AssertionError;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class ThrowPlaceholderConsumer extends PlaceholderConsumer
{
    public function consume(Feed $feed, EntitySequence $entities): void
    {
        throw new AssertionError("PlaceholderConsumer wasn't supposed to be used");
    }
}
