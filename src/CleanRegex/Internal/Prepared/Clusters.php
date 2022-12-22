<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ArrayClusters;
use TRegx\CleanRegex\Internal\Prepared\Cluster\CountedClusters;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class Clusters implements Condition
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

    public function clusters(): CountedClusters
    {
        return new ArrayClusters($this->clusters);
    }

    public function suitable(string $candidate): bool
    {
        foreach ($this->clusters as $cluster) {
            if (!$cluster->suitable($candidate)) {
                return false;
            }
        }
        return true;
    }
}
