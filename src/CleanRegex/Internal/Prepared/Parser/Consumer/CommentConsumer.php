<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class CommentConsumer implements Consumer
{
    /** @var Convention */
    private $convention;

    public function __construct(Convention $convention)
    {
        $this->convention = $convention;
    }

    public function condition(Feed $feed): Condition
    {
        return new CommentCondition($feed);
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $strings = '';
        $commentEnd = $feed->oneOf($this->convention->lineEndings());
        while (!$feed->empty()) {
            if ($commentEnd->consumable()) {
                $strings .= $commentEnd->asString();
                $commentEnd->commit();
                break;
            }
            $letter = $feed->letter();
            $strings .= $letter->asString();
            $letter->commit();
        }
        $entities->append(new Comment($strings));
    }
}
