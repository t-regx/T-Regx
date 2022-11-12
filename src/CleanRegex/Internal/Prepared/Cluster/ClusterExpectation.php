<?php
namespace TRegx\CleanRegex\Internal\Prepared\Cluster;

use TRegx\CleanRegex\Exception\PlaceholderFigureException;

class ClusterExpectation
{
    /** @var CountedClusters */
    private $clusters;
    /** @var int */
    private $expectations = 0;

    public function __construct(CountedClusters $clusters)
    {
        $this->clusters = $clusters;
    }

    public function expectNext(): void
    {
        $this->expectations++;
    }

    public function meetExpectation(): void
    {
        $count = $this->clusters->count();
        if ($this->expectations < $count) {
            throw new PlaceholderFigureException("Supplied a superfluous figure. Used $this->expectations placeholders, but $count figures supplied.");
        }
        if ($this->expectations > $count) {
            throw new PlaceholderFigureException("Not enough corresponding figures supplied. Used $this->expectations placeholders, but $count figures supplied.");
        }
    }
}
