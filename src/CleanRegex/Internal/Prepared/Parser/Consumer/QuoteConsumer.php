<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Span;

class QuoteConsumer implements Consumer
{
    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $feed->commit('\Q');
        $this->consumeSpan($feed, $entities, $feed->stringBefore('\E'));
    }

    private function consumeSpan(Feed $feed, EntitySequence $entities, Span $quote): void
    {
        $feed->commit($quote->content());
        if ($quote->closed()) {
            $feed->commit('\E');
        }
        $entities->append(new Quote($quote->content(), $quote->closed()));
    }
}
