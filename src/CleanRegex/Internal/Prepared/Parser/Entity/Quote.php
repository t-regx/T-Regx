<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Chars;
use TRegx\CleanRegex\Internal\Prepared\Phrase\ConjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Quote implements Entity
{
    use TransitiveFlags;

    /** @var Chars */
    private $quote;
    /** @var bool */
    private $closed;

    public function __construct(string $quote, bool $closed)
    {
        $this->quote = new Chars($quote);
        $this->closed = $closed;
    }

    public function phrase(): Phrase
    {
        if ($this->closed) {
            return new PatternPhrase("\Q$this->quote\E");
        }
        if ($this->quote->endsWith('\\')) {
            return new ConjugatedPhrase("\Q$this->quote\E", "\Q$this->quote");
        }
        return new PatternPhrase("\Q$this->quote");
    }
}
