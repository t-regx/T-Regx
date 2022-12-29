<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\ShortestSubstring;

class CommentConsumer implements Consumer
{
    /** @var Feed */
    private $feed;
    /** @var string[] */
    private $lineEndings;

    public function __construct(Feed $feed, Convention $convention)
    {
        $this->feed = $feed;
        $this->lineEndings = $convention->lineEndings();
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        $this->consumeComment($entities, $this->commentString());
    }

    private function consumeComment(EntitySequence $entities, ShortestSubstring $comment): void
    {
        $closedComment = $comment->closedContent($this->feed);
        $this->feed->commit($closedComment);
        $entities->append(new Comment($closedComment));
    }

    private function commentString(): ShortestSubstring
    {
        $content = $this->feed->content();
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
