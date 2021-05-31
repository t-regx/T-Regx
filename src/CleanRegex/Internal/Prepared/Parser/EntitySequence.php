<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;

class EntitySequence
{
    /** @var Entity[] */
    private $entities;
    /** @var Subpattern */
    private $subpattern;

    public function __construct(Flags $flags)
    {
        $this->entities = [];
        $this->subpattern = new Subpattern(new FlagStack($flags));
    }

    public function append(Entity $entity): void
    {
        $this->entities[] = $entity;
        $entity->visit($this->subpattern);
    }

    public function flags(): Flags
    {
        return $this->subpattern->flags();
    }

    public function entities(): array
    {
        return $this->entities;
    }
}
