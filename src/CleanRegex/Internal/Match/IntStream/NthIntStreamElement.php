<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\RejectedOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\Message;
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

    public function optional(int $index): Optional
    {
        try {
            return $this->unmatchedOptional($index);
        } catch (UnmatchedStreamException $exception) {
            return $this->rejectedOptional($this->messages->messageUnmatched($index));
        }
    }

    private function unmatchedOptional(int $index): Optional
    {
        $elements = \array_values($this->upstream->all());
        if (\array_key_exists($index, $elements)) {
            return new PresentOptional($elements[$index]);
        }
        return $this->rejectedOptional($this->messages->messageInsufficient($index, \count($elements)));
    }

    private function rejectedOptional(Message $message): RejectedOptional
    {
        return new RejectedOptional(new NoSuchNthElementException($message->getMessage()));
    }
}
