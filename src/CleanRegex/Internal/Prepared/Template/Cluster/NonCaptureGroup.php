<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Cluster;

use TRegx\CleanRegex\Internal\Prepared\Phrase\NonCaptureGroupPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\Figure;
use TRegx\CleanRegex\Internal\Type\Type;

class NonCaptureGroup implements Cluster
{
    /** @var Figure */
    private $figure;

    public function __construct(Figure $figure)
    {
        $this->figure = $figure;
    }

    public function phrase(): Phrase
    {
        return new NonCaptureGroupPhrase($this->figure->phrase());
    }

    public function suitable(string $candidate): bool
    {
        return $this->figure->suitable($candidate);
    }

    public function type(): Type
    {
        return $this->figure->type();
    }
}
