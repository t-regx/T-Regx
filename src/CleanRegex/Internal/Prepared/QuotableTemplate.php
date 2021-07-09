<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use Generator;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Figure\ExpectedFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Quotable\CompositeQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class QuotableTemplate
{
    /** @var ExpectedFigures */
    private $figures;
    /** @var PatternAsEntities */
    private $patternAsEntities;

    public function __construct(Orthography $orthography, CountedFigures $figures)
    {
        $this->figures = new ExpectedFigures($figures);
        $this->patternAsEntities = new PatternAsEntities($orthography->pattern(), $orthography->flags(), new FiguresPlaceholderConsumer($this->figures));
    }

    public function quotable(): Quotable
    {
        return new CompositeQuotable(\iterator_to_array($this->quotables()));
    }

    private function quotables(): Generator
    {
        $entities = $this->patternAsEntities->entities();
        $this->figures->meetExpectation();
        foreach ($entities as $entity) {
            yield $entity->quotable();
        }
    }
}
