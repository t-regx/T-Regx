<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;

class Literal implements Expression
{
    use StrictInterpretation;

    /** @var string */
    private $text;
    /** @var Flags */
    private $flags;

    public function __construct(string $text, Flags $flags)
    {
        $this->text = $text;
        $this->flags = $flags;
    }

    protected function phrase(): Phrase
    {
        return new UnconjugatedPhrase(new TextWord($this->text));
    }

    protected function delimiter(): Delimiter
    {
        return new Delimiter('/');
    }

    protected function flags(): Flags
    {
        return $this->flags;
    }
}
