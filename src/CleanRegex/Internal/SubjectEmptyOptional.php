<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Message\Message;
use TRegx\CleanRegex\Match\Optional;

class SubjectEmptyOptional implements Optional
{
    use EmptyOptional;

    /** @var Message */
    private $message;
    /** @var Subject */
    private $subject;

    public function __construct(Subject $subject, Message $message)
    {
        $this->message = $message;
        $this->subject = $subject;
    }

    public function get()
    {
        throw new SubjectNotMatchedException($this->message, $this->subject);
    }
}
