<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Message\Message;

class SubjectNotMatchedException extends \RuntimeException implements PatternException
{
    /** @var string */
    private $subject;

    public function __construct(Message $message, string $subject)
    {
        parent::__construct($message->getMessage());
        $this->subject = $subject;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }
}
