<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Placeholders;

use Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer\ThrowPlaceholderConsumer;
use Test\Utils\Assertion\Fails;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\Placeholders;

class ThrowPlaceholders implements Placeholders
{
    use Fails;

    public function consumer(Feed $feed): PlaceholderConsumer
    {
        return new ThrowPlaceholderConsumer();
    }

    public function meetExpectation(): void
    {
        throw $this->fail();
    }
}
