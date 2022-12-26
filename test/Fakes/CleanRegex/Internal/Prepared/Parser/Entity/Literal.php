<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\TransitiveFlags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Literal implements Entity
{
    use TransitiveFlags;

    /** @var string */
    private $letters;

    public function __construct(string $letters)
    {
        $this->letters = $letters;
    }

    public function phrase(): Phrase
    {
        return new PatternPhrase($this->letters);
    }
}
