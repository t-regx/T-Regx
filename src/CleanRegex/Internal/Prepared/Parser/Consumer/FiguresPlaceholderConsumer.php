<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Placeholder;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class FiguresPlaceholderConsumer implements PlaceholderConsumer
{
    /** @var Feed */
    private $feed;
    /** @var ExpectedClusters */
    private $clusters;

    public function __construct(Feed $feed, ExpectedClusters $clusters)
    {
        $this->feed = $feed;
        $this->clusters = $clusters;
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        $cluster = $this->clusters->current();
        $entities->append(new Placeholder($cluster, $entities->flags()));
        $this->clusters->next();
    }
}
