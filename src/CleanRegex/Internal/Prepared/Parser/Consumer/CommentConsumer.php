<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\ShortestSubstring;

class CommentConsumer implements Consumer
{
    /** @var string[] */
    private $lineEndings;

    public function __construct(Convention $convention)
    {
        $this->lineEndings = $convention->lineEndings();
    }

    public function condition(Feed $feed): Condition
    {
        return new CommentCondition($feed);
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $this->consumeComment($feed, $entities, $this->commentString($feed));
    }

    private function consumeComment(Feed $feed, EntitySequence $entities, ShortestSubstring $comment): void
    {
        $closedComment = $comment->closedContent($feed);
        $feed->commit($closedComment);
        $entities->append(new Comment($closedComment));
    }

    private function commentString(Feed $feed): ShortestSubstring
    {
        $content = $feed->content();
        $comment = new ShortestSubstring();
        foreach ($this->lineEndings as $lineEnding) {
            $this->updateComment($comment, $content, $lineEnding);
        }
        return $comment;
    }

    private function updateComment(ShortestSubstring $comment, string $content, string $lineEnding): void
    {
        $length = \strPos($content, $lineEnding);
        if ($length === false) {
            return;
        }
        $comment->update($length, $lineEnding);
    }
}
