<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\NullCluster;

class ArrayClusters implements CountedClusters
{
    /** @var Cluster[] */
    private $clusters;

    public function __construct(array $clusters)
    {
        $this->clusters = \array_slice($clusters, 0);
    }

    public function nextCluster(): Cluster
    {
        $key = \key($this->clusters);
        if ($key === null) {
            return new NullCluster();
        }
        $value = \current($this->clusters);
        \next($this->clusters);
        return $value;
    }

    public function count(): int
    {
        return \count($this->clusters);
    }
}
