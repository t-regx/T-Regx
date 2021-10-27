<?php
namespace TRegx\CleanRegex\Internal\Match\IntStream;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\RejectedOptional;
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

    public function optional(int $index): Optional
    {
        try {
            return $this->unmatchedOptional($index);
        } catch (UnmatchedStreamException $exception) {
            return new RejectedOptional($this->unmatchedNth($index));
        }
    }

    private function unmatchedOptional(int $index): Optional
    {
        $elements = \array_values($this->upstream->all());
        if (\array_key_exists($index, $elements)) {
            return new PresentOptional($elements[$index]);
        }
        return new RejectedOptional($this->insufficientNth($index, \count($elements)));
    }

    private function unmatchedNth(int $index): Rejection
    {
        return new Rejection($this->subject, SubjectNotMatchedException::class, $this->messages->messageUnmatched($index));
    }

    private function insufficientNth(int $index, int $count): Rejection
    {
        return new Rejection($this->subject, NoSuchNthElementException::class, $this->messages->messageInsufficient($index, $count));
    }
}
