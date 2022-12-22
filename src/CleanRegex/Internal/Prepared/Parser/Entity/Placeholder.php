<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class Placeholder implements Entity
{
    use TransitiveFlags;

    /** @var Cluster */
    private $cluster;
    /** @var SubpatternFlags */
    private $flags;

    public function __construct(Cluster $cluster, SubpatternFlags $flags)
    {
        $this->cluster = $cluster;
        $this->flags = $flags;
    }

    public function phrase(): Phrase
    {
        return $this->cluster->phrase($this->flags);
    }
}
