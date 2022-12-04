<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Template\DelimiterAgnostic;
use TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord;

class AlterationFigure implements Figure
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
        return new UnconjugatedPhrase(new AlterationWord($this->figures));
    }
}
