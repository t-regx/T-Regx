<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Internal\EmptyOptional;
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

    public function optional(int $index): Optional
    {
        try {
            return $this->unmatchedOptional($index);
        } catch (UnmatchedStreamException $exception) {
            return new EmptyOptional(new NoSuchNthElementException((new SubjectNotMatched\FromNthStreamMessage($index))->getMessage()));
        }
    }

    private function unmatchedOptional(int $index): Optional
    {
        $elements = \array_values($this->upstream->all());
        if (\array_key_exists($index, $elements)) {
            return new PresentOptional($elements[$index]);
        }
        return new EmptyOptional(new NoSuchNthElementException((new FromNthStreamMessage($index, \count($elements)))->getMessage()));
    }
}
