<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Placeholder implements Entity
{
    use TransitiveFlags;

    /** @var ExpectedClusters */
    private $clusters;
    /** @var SubpatternFlags */
    private $flags;

    public function __construct(ExpectedClusters $clusters, SubpatternFlags $flags)
    {
        $this->clusters = $clusters;
        $this->flags = $flags;
    }

    public function phrase(): Phrase
    {
        $cluster = $this->clusters->current();
        $this->clusters->next();
        return $cluster->phrase($this->flags);
    }
}
