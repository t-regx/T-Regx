<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\AlterationWord;

class Alteration implements Expression
{
    use StrictInterpretation;

    /** @var array */
    private $texts;
    /** @var Flags */
    private $flags;

    public function __construct(array $texts, Flags $flags)
    {
        $this->texts = $texts;
        $this->flags = $flags;
    }

    protected function phrase(): Phrase
    {
        return new UnconjugatedPhrase(new AlterationWord($this->texts));
    }

    protected function delimiter(): Delimiter
    {
        return new Delimiter('/');
    }

    protected function flags(): Flags
    {
        return $this->flags;
    }

    protected function undevelopedInput(): string
    {
        // We should come up with a better idea when we expose
        // undeveloped inputs as a public API
        return \implode('|', $this->texts);
    }
}
