<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class EscapeConsumer implements Consumer
{
    /** @var Feed */
    private $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        if ($this->feed->empty()) {
            throw new TrailingBackslashException();
        }
        $letterString = $this->feed->firstLetter();
        $this->feed->commitSingle();
        $entities->append(new Escaped($letterString));
    }
}
