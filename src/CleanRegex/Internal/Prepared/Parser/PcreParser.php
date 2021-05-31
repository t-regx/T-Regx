<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\Consumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

class PcreParser
{
    /** @var Feed */
    private $feed;
    /** @var EntitySequence */
    private $sequence;
    /** @var Consumer[] */
    private $consumers;

    public function __construct(Feed $feed, Flags $flags, array $consumers)
    {
        $this->feed = $feed;
        $this->sequence = new EntitySequence($flags);
        $this->consumers = $consumers;
    }

    /**
     * @return Entity[]
     */
    public function entities(): array
    {
        while (!$this->feed->empty()) {
            $this->applicableConsumer()->consume($this->feed, $this->sequence);
        }
        return $this->sequence->entities();
    }

    private function applicableConsumer(): Consumer
    {
        foreach ($this->consumers as $consumer) {
            $condition = $consumer->condition($this->feed);
            if ($condition->met($this->sequence)) {
                $condition->commit();
                return $consumer;
            }
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
