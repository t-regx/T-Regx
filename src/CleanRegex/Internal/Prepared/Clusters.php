<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ArrayClusters;
use TRegx\CleanRegex\Internal\Prepared\Cluster\CountedClusters;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class Clusters
{
    /** @var Cluster[] */
    private $clusters;

    public function __construct(array $clusters)
    {
        $this->clusters = $clusters;
    }

    public function next(Cluster $cluster): Clusters
    {
        return new Clusters(\array_merge($this->clusters, [$cluster]));
    }

    public function condition(): Condition
    {
        return new CompositeCondition($this->clusters);
    }

    public function clusters(): CountedClusters
    {
        return new ArrayClusters($this->clusters);
    }
}
