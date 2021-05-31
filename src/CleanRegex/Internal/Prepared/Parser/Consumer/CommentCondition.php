<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\ConstantString;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class CommentCondition implements Condition
{
    /** @var Feed */
    private $feed;
    /** @var ConstantString */
    private $string;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
        $this->string = $this->feed->string('#');
    }

    public function met(EntitySequence $entities): bool
    {
        return $this->string->consumable() && $entities->flags()->has('x');
    }

    public function commit(): void
    {
        $this->string->consume();
    }
}
