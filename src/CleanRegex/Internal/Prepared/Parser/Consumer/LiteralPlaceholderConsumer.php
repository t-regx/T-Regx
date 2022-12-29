<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class LiteralPlaceholderConsumer implements PlaceholderConsumer
{
    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $feed->commitSingle();
        $entities->appendLiteral('@');
    }
}
