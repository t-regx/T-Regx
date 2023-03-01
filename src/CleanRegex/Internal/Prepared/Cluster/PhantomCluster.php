<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;

class PhantomCluster implements Cluster
{
    public function phrase(SubpatternFlags $flags): Phrase
    {
        return new PhantomPhrase();
    }

    public function suitable(string $candidate): bool
    {
        return true;
    }
}