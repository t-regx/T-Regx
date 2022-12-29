<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class LiteralPlaceholderConsumer implements PlaceholderConsumer
{
    /** @var Feed */
    private $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        $entities->appendLiteral('@');
    }
}
