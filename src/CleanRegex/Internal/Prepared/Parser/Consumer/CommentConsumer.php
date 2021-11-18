<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\StringConditions;

class CommentConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return new CommentCondition($feed);
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $strings = new StringConditions();
        $this->commitToStrings($feed, $strings);
        $entities->append(new Comment($strings->asString()));
    }

    private function commitToStrings(Feed $feed, StringConditions $strings): void
    {
        $commentEnd = $feed->string("\n");
        while (!$feed->empty()) {
            if ($commentEnd->consumable()) {
                $strings->add($commentEnd);
                break;
            }
            $strings->add($feed->letter());
        }
    }
}
