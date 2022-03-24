<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Message\Stream\FromNthStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Optional;

class NthStreamElement
{
    /** @var Upstream */
    private $upstream;
    /** @var Subject */
    public $subject;

    public function __construct(Upstream $upstream, Subject $subject)
    {
        $this->upstream = $upstream;
        $this->subject = $subject;
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
        return $this->rejection(new SubjectNotMatched\FromNthStreamMessage($index));
    }

    private function insufficientNth(int $index, int $count): Rejection
    {
        return $this->rejection(new FromNthStreamMessage($index, $count));
    }

    private function rejection(NotMatchedMessage $message): Rejection
    {
        return new Rejection($this->subject, NoSuchNthElementException::class, $message);
    }
}
