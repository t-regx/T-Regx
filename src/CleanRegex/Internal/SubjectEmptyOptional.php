<?php
namespace TRegx\CleanRegex\Internal;

use Throwable;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

class SubjectEmptyOptional implements Optional
{
    use EmptyOptional;

    /** @var NotMatched */
    private $notMatched;
    /** @var Message */
    private $message;
    /** @var Subject */
    private $subject;

    public function __construct(GroupAware $groupAware, Subject $subject, Message $message)
    {
        $this->notMatched = new NotMatched($groupAware, $subject);
        $this->message = $message;
        $this->subject = $subject;
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer($this->notMatched);
    }

    public function get()
    {
        throw new SubjectNotMatchedException($this->message, $this->subject);
    }

    public function orThrow(Throwable $throwable = null): void
    {
        if ($throwable === null) {
            throw new SubjectNotMatchedException($this->message, $this->subject);
        }
        throw $throwable;
    }
}
