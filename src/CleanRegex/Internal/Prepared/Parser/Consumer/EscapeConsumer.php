<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class EscapeConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('\\');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        if ($feed->empty()) {
            throw new TrailingBackslashException();
        }
        $letterString = $feed->firstLetter();
        $feed->shiftSingle();
        $entities->append(new Escaped($letterString));
    }
}
