<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class ArrayClusters implements CountedClusters
{
    /** @var Cluster[] */
    private $clusters;

    public function __construct(array $clusters)
    {
        $this->clusters = \array_slice($clusters, 0);
    }

    public function current(): Cluster
    {
        $cluster = \current($this->clusters);
        if ($cluster === false) {
            return new PhantomCluster();
        }
        return $cluster;
    }

    public function next(): void
    {
        \next($this->clusters);
    }

    public function count(): int
    {
        return \count($this->clusters);
    }
}
