<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use Throwable;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class ThrowStrategy implements SubjectRs, MatchRs
{
    /** @var Throwable|null */
    private $throwable;
    /** @var Message */
    private $message;

    public function __construct(?Throwable $throwable, Message $message)
    {
        $this->throwable = $throwable;
        $this->message = $message;
    }

    public function substitute(Subject $subject): string
    {
        if ($this->throwable === null) {
            throw new GroupNotMatchedException($this->message->getMessage());
        }
        throw $this->throwable;
    }

    public function substituteGroup(Detail $detail): string
    {
        if ($this->throwable === null) {
            throw new GroupNotMatchedException($this->message->getMessage());
        }
        throw $this->throwable;
    }
}
