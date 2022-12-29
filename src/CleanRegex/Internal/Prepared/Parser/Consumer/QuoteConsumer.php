<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Span;

class QuoteConsumer implements Consumer
{
    /** @var Feed */
    private $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commit('\Q');
        $this->consumeSpan($entities, $this->feed->stringBefore('\E'));
    }

    private function consumeSpan(EntitySequence $entities, Span $quote): void
    {
        $this->feed->commit($quote->content());
        if ($quote->closed()) {
            $this->feed->commit('\E');
        }
        $entities->append(new Quote($quote->content(), $quote->closed()));
    }
}
