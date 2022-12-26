<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Phrase\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class EntitySequence
{
    /** @var Subpattern */
    private $subpattern;

    /** @var Phrase[] */
    private $phrases = [];
    /** @var string */
    private $literal = '';

    public function __construct(SubpatternFlags $flags)
    {
        $this->subpattern = new Subpattern($flags);
    }

    public function append(Entity $entity): void
    {
        if ($this->literal !== '') {
            $this->phrases[] = new PatternPhrase($this->literal);
            $this->literal = '';
        }
        $this->phrases[] = $entity->phrase();
        $entity->visit($this->subpattern);
    }

    public function appendLiteral(string $literal): void
    {
        $this->literal .= $literal;
    }

    public function flags(): SubpatternFlags
    {
        return $this->subpattern->flags();
    }

    public function phrases(): array
    {
        if ($this->literal !== '') {
            $this->phrases[] = new PatternPhrase($this->literal);
            $this->literal = '';
        }
        return $this->phrases;
    }
}
