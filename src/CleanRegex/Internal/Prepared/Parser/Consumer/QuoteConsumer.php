<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class QuoteConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('\Q');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append($this->consumeQuote($feed));
    }

    private function consumeQuote(Feed $feed): Quote
    {
        $quote = '';
        $quoteEnd = $feed->string('\E');
        while (!$feed->empty()) {
            if ($quoteEnd->consumable()) {
                $quoteEnd->consume();
                return new Quote($quote, true);
            }
            $quote .= $feed->letter()->consume();
        }
        return new Quote($quote, false);
    }
}
