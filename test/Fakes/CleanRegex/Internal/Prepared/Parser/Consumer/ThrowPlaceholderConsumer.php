<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class ThrowPlaceholderConsumer implements PlaceholderConsumer
{
    use Fails;

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        throw $this->fail();
    }
}
