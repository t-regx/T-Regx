<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\AtomicGroup;
use TRegx\CleanRegex\Internal\Prepared\Template\Cluster\Cluster;
use TRegx\CleanRegex\Internal\Prepared\Template\Figure\LiteralFigure;
use TRegx\CleanRegex\Internal\Type\ValueType;

class FigureClusters implements CountedClusters
{
    /** @var string[] */
    private $figures;

    /**
     * @param string[] $figures
     */
    public function __construct(array $figures)
    {
        foreach ($figures as $figure) {
            if (\is_string($figure)) {
                continue;
            }
            throw InvalidArgument::typeGiven("Invalid inject figure type. Expected string", new ValueType($figure));
        }
        $this->figures = \array_slice($figures, 0);
    }

    public function current(): Cluster
    {
        return new AtomicGroup(new LiteralFigure(\current($this->figures)));
    }

    public function next(): void
    {
        \next($this->figures);
    }

    public function count(): int
    {
        return \count($this->figures);
    }
}
