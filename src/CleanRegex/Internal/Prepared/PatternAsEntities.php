<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use Generator;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class PatternAsEntities
{
    /** @var PatternEntities */
    private $entities;

    public function __construct(string $pattern, Flags $flags, PlaceholderConsumer $placeholderConsumer)
    {
        $this->entities = new PatternEntities($pattern, $flags, $placeholderConsumer);
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
        return new CompositePhrase(\iterator_to_array($this->phrases()));
    }

    private function phrases(): Generator
    {
        foreach ($this->entities->entities() as $entity) {
            yield $entity->phrase();
        }
    }
}
