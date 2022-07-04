<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\NullCluster;

class IndividualCluster implements CountedClusters
{
    /** @var Cluster|null */
    private $cluster;

    public function __construct(Cluster $cluster)
    {
        $this->cluster = $cluster;
    }

    public function nextCluster(): Cluster
    {
        if ($this->cluster === null) {
            return new NullCluster();
        }
        $cluster = $this->cluster;
        $this->cluster = null;
        return $cluster;
    }

    public function count(): int
    {
        return 1;
    }
}
