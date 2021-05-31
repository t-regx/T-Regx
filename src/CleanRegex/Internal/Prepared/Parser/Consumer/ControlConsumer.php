<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Control;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class ControlConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('\c');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append($this->consumeControl($feed));
    }

    private function consumeControl(Feed $feed): Entity
    {
        $letter = $feed->letter();
        if ($letter->consumable()) {
            return new Control($letter->consume());
        }
        return new Control('');
    }
}
