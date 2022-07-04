<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\NullCluster;
use UnderflowException;

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

    public function nextCluster(): Cluster
    {
        $this->expectation->expectNext();
        try {
            return $this->clusters->nextCluster();
        } catch (UnderflowException $exception) {
            return new NullCluster();
        }
    }

    public function meetExpectation(): void
    {
        $this->expectation->meetExpectation();
    }
}
