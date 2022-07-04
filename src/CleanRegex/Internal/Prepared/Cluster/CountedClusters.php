<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

interface CountedClusters
{
    public function current(): Cluster;

    public function next(): void;

    public function count(): int;
}
