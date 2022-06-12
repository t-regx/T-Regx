<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Message\Stream\FromNthStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;
use TRegx\CleanRegex\Match\Optional;

class NthStreamElement
{
    /** @var Upstream */
    private $upstream;

    public function __construct(Upstream $upstream)
    {
        $this->upstream = $upstream;
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

    public function value(Index $index)
    {
        try {
            return $this->unmatchedValue($index);
        } catch (UnmatchedStreamException $exception) {
            throw new NoSuchNthElementException((new SubjectNotMatched\FromNthStreamMessage($index))->getMessage());
        }
    }

    private function unmatchedValue(Index $index)
    {
        $elements = \array_values($this->upstream->all());
        if ($index->in($elements)) {
            return $index->valueFrom($elements);
        }
        throw new NoSuchNthElementException((new FromNthStreamMessage($index, \count($elements)))->getMessage());
    }
}
