<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;

class EntitySequence
{
    /** @var Entity[] */
    private $entities;
    /** @var Subpattern */
    private $subpattern;
    /** @var string */
    private $literal = '';

    public function __construct(SubpatternFlags $flags)
    {
        $this->entities = [];
        $this->subpattern = new Subpattern($flags);
    }

    public function append(Entity $entity): void
    {
        if ($this->literal !== '') {
            $this->entities[] = new Literal($this->literal);
            $this->literal = '';
        }
        $this->entities[] = $entity;
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

    public function entities(): array
    {
        if ($this->literal !== '') {
            $this->entities[] = new Literal($this->literal);
            $this->literal = '';
        }
        return $this->entities;
    }
}
