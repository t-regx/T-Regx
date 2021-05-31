<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Posix;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class PosixConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('[');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append(new PosixOpen());
        $quoteConsumer = new QuoteConsumer();
        $posix = '';
        while (true) {
            $closingTag = $feed->string(']');
            if ($closingTag->consumable()) {
                $closingTag->consume();
                if ($posix !== '') {
                    $entities->append(new Posix($posix));
                }
                $entities->append(new PosixClose());
                return;
            }
            $condition = $quoteConsumer->condition($feed);
            if ($condition->met($entities)) {
                $condition->commit();
                if ($posix !== '') {
                    $entities->append(new Posix($posix));
                    $posix = '';
                }
                $quoteConsumer->consume($feed, $entities);
                continue;
            }
            $feedLetter = $feed->letter();
            if (!$feedLetter->consumable()) {
                break;
            }
            $letter = $feedLetter->consume();
            if ($letter !== '\\') {
                $posix .= $letter;
            } else {
                $escapedLetter = $feed->letter();
                if ($escapedLetter->consumable()) {
                    $escaped = $escapedLetter->consume();
                    $posix .= "\\$escaped";
                } else {
                    $posix .= "\\";
                }
            }
        }
        if ($posix !== '') {
            $entities->append(new Posix($posix));
        }
    }
}
