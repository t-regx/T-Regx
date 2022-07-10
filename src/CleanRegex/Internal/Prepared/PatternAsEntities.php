<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Pattern\StringPattern;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\Placeholders;

class PatternAsEntities
{
    /** @var PatternEntities */
    private $entities;
    /** @var PatternPhrase */
    private $phrase;

    public function __construct(StringPattern $pattern, Placeholders $placeholders)
    {
        $this->entities = new PatternEntities($pattern, $placeholders->consumer());
        $this->phrase = new PatternPhrase($pattern, $placeholders);
    }

    /**
     * @return Entity[]
     */
    public function entities(): array
    {
        return $this->entities->entities();
    }

    public function phrase(): Phrase
    {
        return $this->phrase->phrase();
    }
}
