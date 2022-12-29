<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class GroupCloseConsumer implements Consumer
{
    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $feed->commitSingle();
        $entities->append(new GroupClose());
    }
}
