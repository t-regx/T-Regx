<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use Generator;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Figure\ExpectedFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Word\CompositeWord;

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

    public function compositeWord(): CompositeWord
    {
        return new CompositeWord(\iterator_to_array($this->words()));
    }

    private function words(): Generator
    {
        $entities = $this->patternAsEntities->entities();
        $this->figures->meetExpectation();
        foreach ($entities as $entity) {
            yield $entity->word();
        }
    }
}
