<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use Generator;
use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Pattern\StringPattern;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\Placeholders;

class PatternPhrase
{
    /** @var PatternEntities */
    private $entities;
    /** @var Placeholders */
    private $placeholders;

    public function __construct(GroupAutoCapture $autoCapture, StringPattern $pattern, Placeholders $placeholders)
    {
        $this->entities = new PatternEntities($pattern, $autoCapture, $placeholders->consumer());
        $this->placeholders = $placeholders;
    }

    public function phrase(): Phrase
    {
        return new CompositePhrase(\iterator_to_array($this->phrases()));
    }

    private function phrases(): Generator
    {
        $entities = $this->entities->entities();
        $this->placeholders->meetExpectation();
        foreach ($entities as $entity) {
            yield $entity->phrase();
        }
    }
}
