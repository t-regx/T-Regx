<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\NonCaptureGroupPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\Figure;

class NonCaptureGroup implements Cluster
{
    /** @var Figure */
    private $figure;

    public function __construct(Figure $figure)
    {
        $this->figure = $figure;
    }

    public function phrase(SubpatternFlags $flags): Phrase
    {
        return new NonCaptureGroupPhrase($this->figure->phrase($flags));
    }

    public function suitable(string $candidate): bool
    {
        return $this->figure->suitable($candidate);
    }
}
