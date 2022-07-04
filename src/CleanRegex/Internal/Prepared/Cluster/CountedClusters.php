<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

interface CountedClusters
{
    public function nextCluster(): Cluster;

    public function count(): int;
}
