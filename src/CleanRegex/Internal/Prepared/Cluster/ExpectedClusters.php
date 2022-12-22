<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class ExpectedClusters
{
    /** @var ClusterExpectation */
    private $expectation;
    /** @var CountedClusters */
    private $clusters;

    public function __construct(CountedClusters $clusters)
    {
        $this->expectation = new ClusterExpectation($clusters);
        $this->clusters = $clusters;
    }

    public function current(): Cluster
    {
        return $this->clusters->current();
    }

    public function next(): void
    {
        $this->clusters->next();
        $this->expectation->expectNext();
    }

    public function meetExpectation(): void
    {
        $this->expectation->meetExpectation();
    }
}
