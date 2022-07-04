<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class Placeholder implements Entity
{
    use TransitiveFlags;

    /** @var Cluster */
    private $cluster;

    public function __construct(Cluster $cluster)
    {
        $this->cluster = $cluster;
    }

    public function phrase(): Phrase
    {
        return $this->cluster->phrase();
    }
}
