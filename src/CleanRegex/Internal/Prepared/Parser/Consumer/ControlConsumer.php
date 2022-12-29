<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Control;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class ControlConsumer implements Consumer
{
    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $feed->commit('\c');
        if ($feed->empty()) {
            $entities->append(new Control(''));
        } else {
            $entities->append(new Control($feed->firstLetter()));
            $feed->commitSingle();
        }
    }
}
