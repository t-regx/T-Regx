<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class CommentConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return new CommentCondition($feed);
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append($this->consumeComment($feed));
    }

    private function consumeComment(Feed $feed): Comment
    {
        $comment = '';
        while (!$feed->empty()) {
            $commentEnd = $feed->string("\n");
            if ($commentEnd->consumable()) {
                $commentEnd->commit();
                return new Comment($comment, true);
            }
            $comment .= $feed->letter()->consume();
        }
        return new Comment($comment, false);
    }
}
