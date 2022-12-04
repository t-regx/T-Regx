<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Template\DelimiterAgnostic;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;

class LiteralFigure implements Figure
{
    use DelimiterAgnostic;

    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function phrase(SubpatternFlags $flags): Phrase
    {
        return new UnconjugatedPhrase(new TextWord($this->text));
    }
}
