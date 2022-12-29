<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CharacterClassConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CommentConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\Consumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\ControlConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class PcreParser
{
    /** @var Feed */
    private $feed;
    /** @var EntitySequence */
    private $sequence;
    /** @var CommentConsumer */
    private $commentConsumer;
    /** @var Consumer[] */
    private $consumers;
    /** @var Consumer[] */
    private $controlConsumers;
    /** @var EscapeConsumer */
    private $escapedConsumer;

    public function __construct(Feed                $feed,
                                SubpatternFlags     $flags,
                                GroupAutoCapture    $autoCapture,
                                PlaceholderConsumer $placeholderConsumer,
                                Convention          $convention)
    {
        $this->feed = $feed;
        $this->sequence = new EntitySequence($flags);
        $this->commentConsumer = new CommentConsumer($convention);
        $this->consumers = [
            '(' => new GroupConsumer($autoCapture),
            ')' => new GroupCloseConsumer(),
            '@' => $placeholderConsumer,
            '[' => new CharacterClassConsumer(),
        ];
        $this->controlConsumers = [
            '\c' => new ControlConsumer(),
            '\Q' => new QuoteConsumer(),
        ];
        $this->escapedConsumer = new EscapeConsumer();
    }

    /**
     * @return Phrase[]
     */
    public function phrases(): array
    {
        while (!$this->feed->empty()) {
            $literalsAmount = $this->feed->stringLengthBeforeAny('()[@\#');
            if ($literalsAmount > 0) {
                $this->consumeLiteral($literalsAmount);
            } else {
                $this->consumeComposite();
            }
        }
        return $this->sequence->phrases();
    }

    private function consumeLiteral(int $literalsAmount): void
    {
        $literals = $this->feed->subString($literalsAmount);
        $this->sequence->appendLiteral($literals);
        $this->feed->commit($literals);
    }

    private function consumeComposite(): void
    {
        $firstLetter = $this->feed->firstLetter();
        if ($firstLetter === '\\') {
            $this->escapedConsumer()->consume($this->feed, $this->sequence);
        } else if ($firstLetter === '#') {
            $this->consumeComment();
        } else {
            $this->consumers[$firstLetter]->consume($this->feed, $this->sequence);
        }
    }

    private function escapedConsumer(): Consumer
    {
        $head = $this->feed->head();
        if (\in_array($head, ['\c', '\Q'])) {
            return $this->controlConsumers[$head];
        }
        return $this->escapedConsumer;
    }

    private function consumeComment(): void
    {
        if ($this->sequence->flags()->isExtended()) {
            $this->commentConsumer->consume($this->feed, $this->sequence);
        } else {
            $this->sequence->appendLiteral('#');
            $this->feed->commitSingle();
        }
    }
}
