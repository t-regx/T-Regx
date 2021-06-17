<?php
namespace TRegx\CleanRegex\Internal\Prepared\Figure;

class FigureExpectation
{
    /** @var CountedFigures */
    private $figures;
    /** @var int */
    private $figuresCount = 0;

    public function __construct(CountedFigures $figures)
    {
        $this->figures = $figures;
    }

    public function expectNext(): void
    {
        $this->figuresCount++;
    }

    public function meetExpectation(): void
    {
        $count = $this->figures->count();
        if ($this->figuresCount < $count) {
            throw PlaceholderFigureException::forSuperfluousFigures($this->figuresCount, $count, $this->figures->nextToken());
        }
        if ($this->figuresCount > $count) {
            throw PlaceholderFigureException::forSuperfluousPlaceholders($this->figuresCount, $count);
        }
    }
}
