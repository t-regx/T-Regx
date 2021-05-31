<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\TerminatingEscape;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class EscapeConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('\\');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append($this->consumeEscaped($feed));
    }

    private function consumeEscaped(Feed $feed): Entity
    {
        $letter = $feed->letter();
        if ($letter->consumable()) {
            return new Escaped($letter->consume());
        }
        return new TerminatingEscape();
    }
}
