<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Placeholder implements Entity
{
    use TransitiveFlags;

    /** @var ExpectedClusters */
    private $clusters;

    public function __construct(ExpectedClusters $clusters)
    {
        $this->clusters = $clusters;
    }

    public function phrase(): Phrase
    {
        $cluster = $this->clusters->current();
        $this->clusters->next();
        return $cluster->phrase();
    }
}
