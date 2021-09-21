<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

trait PatternEntity
{
    public abstract function pattern();

    public function phrase(): Phrase
    {
        return new PatternPhrase($this->pattern());
    }
}
