<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use Generator;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Figure\ExpectedFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Dictionary
{
    /** @var ExpectedFigures */
    private $figures;
    /** @var PatternAsEntities */
    private $patternAsEntities;

    public function __construct(Spelling $spelling, CountedFigures $figures)
    {
        $this->figures = new ExpectedFigures($figures);
        $this->patternAsEntities = new PatternAsEntities($spelling->pattern(), $spelling->flags(), new FiguresPlaceholderConsumer($this->figures));
    }

    public function compositePhrase(): Phrase
    {
        return new CompositePhrase(\iterator_to_array($this->phrases()));
    }

    private function phrases(): Generator
    {
        $entities = $this->patternAsEntities->entities();
        $this->figures->meetExpectation();
        foreach ($entities as $entity) {
            yield $entity->phrase();
        }
    }
}
