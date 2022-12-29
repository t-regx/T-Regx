<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class GroupCloseConsumer implements Consumer
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
        $entities->append(new GroupClose());
    }
}
