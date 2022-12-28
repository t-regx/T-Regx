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
        while (!$feed->empty()) {
            if ($feed->startsWith('\E')) {
                $feed->commit('\E');
                return new Quote($quote, true);
            }
            $quote .= $feed->firstLetter();
            $feed->commitSingle();
        }
        return new Quote($quote, false);
    }
}
