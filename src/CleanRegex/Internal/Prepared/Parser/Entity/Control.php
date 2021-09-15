<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Phrase\ConjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Control implements Entity
{
    use TransitiveFlags;

    /** @var string */
    private $control;

    public function __construct(string $control)
    {
        $this->control = $control;
    }

    public function phrase(): Phrase
    {
        if ($this->control === '\\') {
            return new ConjugatedPhrase('\c\{1}', "\c\\");
        }
        return new PatternPhrase("\c$this->control");
    }
}
