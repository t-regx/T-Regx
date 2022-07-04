<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class IndividualCluster implements CountedClusters
{
    /** @var Cluster */
    private $cluster;

    public function __construct(Cluster $cluster)
    {
        $this->cluster = $cluster;
    }

    public function current(): Cluster
    {
        return $this->cluster;
    }

    public function next(): void
    {
    }

    public function count(): int
    {
        return 1;
    }
}
