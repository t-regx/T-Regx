<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Control;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class ControlConsumer implements Consumer
{
    /** @var Feed */
    private $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commit('\c');
        if ($this->feed->empty()) {
            $entities->append(new Control(''));
        } else {
            $entities->append(new Control($this->feed->firstLetter()));
            $this->feed->commitSingle();
        }
    }
}
