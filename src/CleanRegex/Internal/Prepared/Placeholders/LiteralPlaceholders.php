<?php
namespace TRegx\CleanRegex\Internal\Prepared\Placeholders;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class LiteralPlaceholders implements Placeholders
{
    public function consumer(Feed $feed): PlaceholderConsumer
    {
        return new LiteralPlaceholderConsumer($feed);
    }

    public function meetExpectation(): void
    {
    }
}
