<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Figure\ExpectedFigures;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Placeholder;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class FiguresPlaceholderConsumer extends PlaceholderConsumer
{
    /** @var ExpectedFigures */
    private $figures;

    public function __construct(ExpectedFigures $figures)
    {
        $this->figures = $figures;
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append(new Placeholder($this->figures->nextToken()));
    }
}
