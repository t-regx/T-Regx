<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Optional;

class NthIntStreamElement
{
    /** @var Upstream */
    private $upstream;
    /** @var Subject */
    public $subject;
    /** @var RejectionMessages */
    private $messages;

    public function __construct(Upstream $upstream, Subject $subject, RejectionMessages $messages)
    {
        $this->upstream = $upstream;
        $this->subject = $subject;
        $this->messages = $messages;
    }

    public function optional(Index $index): Optional
    {
        try {
            return $this->unmatchedOptional($index);
        } catch (UnmatchedStreamException $exception) {
            return new EmptyOptional();
        }
    }

    private function unmatchedOptional(Index $index): Optional
    {
        $elements = \array_values($this->upstream->all());
        if ($index->in($elements)) {
            return new PresentOptional($index->valueFrom($elements));
        }
        return new EmptyOptional();
    }

    public function value(Index $index): int
    {
        try {
            return $this->unmatchedValue($index);
        } catch (UnmatchedStreamException $exception) {
            throw new NoSuchNthElementException($this->messages->messageUnmatched($index)->getMessage());
        }
    }

    private function unmatchedValue(Index $index): int
    {
        $elements = \array_values($this->upstream->all());
        if ($index->in($elements)) {
            return $index->valueFrom($elements);
        }
        throw new NoSuchNthElementException($this->messages->messageInsufficient($index, \count($elements))->getMessage());
    }
}
