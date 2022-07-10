<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\Placeholders;

class ThrowPlaceholderConsumer implements Placeholders
{
    use Fails;

    public function consumer(): PlaceholderConsumer
    {
        return new class extends PlaceholderConsumer {
            use Fails;

            public function consume(Feed $feed, EntitySequence $entities): void
            {
                throw $this->fail();
            }
        };
    }

    public function meetExpectation(): void
    {
        throw $this->fail();
    }
}
