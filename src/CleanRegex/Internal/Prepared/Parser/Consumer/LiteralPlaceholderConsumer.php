<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class LiteralPlaceholderConsumer implements PlaceholderConsumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('@');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append(new Literal('@'));
    }
}
