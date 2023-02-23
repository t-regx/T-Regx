<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\FailPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\NonCaptureGroupPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\DelimiterAgnostic;
use TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord;

class AlterationGroup implements Cluster
{
    use DelimiterAgnostic;

    /** @var array */
    private $figures;

    public function __construct(array $figures)
    {
        $this->figures = $figures;
    }

    public function phrase(SubpatternFlags $flags): Phrase
    {
        if (empty($this->figures)) {
            return new FailPhrase();
        }
        return new NonCaptureGroupPhrase(new UnconjugatedPhrase(new AlterationWord($this->figures)));
    }
}
