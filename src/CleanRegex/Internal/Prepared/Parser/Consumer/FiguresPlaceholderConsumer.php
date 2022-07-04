<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Placeholder;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class FiguresPlaceholderConsumer extends PlaceholderConsumer
{
    /** @var ExpectedClusters */
    private $clusters;

    public function __construct(ExpectedClusters $clusters)
    {
        $this->clusters = $clusters;
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append(new Placeholder($this->clusters->current()));
        $this->clusters->next();
        $this->clusters->expectNext();
    }
}
