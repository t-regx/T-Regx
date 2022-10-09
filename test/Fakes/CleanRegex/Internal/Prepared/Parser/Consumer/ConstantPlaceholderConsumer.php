<?php
namespace Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class ConstantPlaceholderConsumer extends PlaceholderConsumer
{
    /** @var string */
    private $figure;

    public function __construct(string $figure)
    {
        $this->figure = $figure;
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append(new Literal($this->figure));
    }
}
