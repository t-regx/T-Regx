<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Exception\PlaceholderFigureException;

class ClusterExpectation
{
    /** @var CountedClusters */
    private $clusters;
    /** @var int */
    private $expectations = 0;

    public function __construct(CountedClusters $clusters)
    {
        $this->clusters = $clusters;
    }

    public function expectNext(): void
    {
        $this->expectations++;
    }

    public function meetExpectation(): void
    {
        $count = $this->clusters->count();
        if ($this->expectations < $count) {
            throw PlaceholderFigureException::forSuperfluousFigures($this->expectations, $count, $this->clusters->nextCluster());
        }
        if ($this->expectations > $count) {
            throw PlaceholderFigureException::forSuperfluousPlaceholders($this->expectations, $count);
        }
    }
}
