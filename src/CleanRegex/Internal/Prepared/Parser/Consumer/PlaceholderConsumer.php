<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

abstract class PlaceholderConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('@');
    }
}
