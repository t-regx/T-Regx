<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Phrase\AtomicGroupPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\Figure;

class AtomicGroup implements Cluster
{
    /** @var Figure */
    private $figure;

    public function __construct(Figure $figure)
    {
        $this->figure = $figure;
    }

    public function phrase(): Phrase
    {
        return new AtomicGroupPhrase($this->figure->phrase());
    }

    public function suitable(string $candidate): bool
    {
        return $this->figure->suitable($candidate);
    }
}
