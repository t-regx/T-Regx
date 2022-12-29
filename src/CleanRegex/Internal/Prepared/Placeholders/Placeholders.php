<?php
namespace TRegx\CleanRegex\Internal\Prepared\Placeholders;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

interface Placeholders
{
    public function consumer(Feed $feed): PlaceholderConsumer;

    public function meetExpectation(): void;
}
