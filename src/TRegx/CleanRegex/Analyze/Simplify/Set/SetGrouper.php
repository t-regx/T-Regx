<?php
namespace TRegx\CleanRegex\Analyze\Simplify\Set;

use TRegx\CleanRegex\Analyze\Simplify\Model\Model;
use TRegx\CleanRegex\Analyze\Simplify\ModelFactory;
use TRegx\CleanRegex\Analyze\Simplify\QuotesBreaker;

class SetGrouper
{
    /** @var QuotesBreaker */
    private $breaker;
    /** @var ModelFactory */
    private $factory;

    public function __construct(QuotesBreaker $breaker, ModelFactory $factory)
    {
        $this->breaker = $breaker;
        $this->factory = $factory;
    }

    /**
     * @return Model[]
     */
    public function getGrouped(): array
    {
        return (new SetGrouperWorker($this->breaker->split(), $this->factory))->getGrouped();
    }
}
