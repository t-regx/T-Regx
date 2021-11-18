<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class LiteralConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return new TrueCondition();
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $letter = $feed->letter();
        $entities->append(new Literal($letter->asString()));
        $letter->commit();
    }
}
