<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer;

use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\Condition;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class ThrowPlaceholderConsumer implements PlaceholderConsumer
{
    use Fails;

    public function condition(Feed $feed): Condition
    {
        return $feed->string('@');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        throw $this->fail();
    }
}
