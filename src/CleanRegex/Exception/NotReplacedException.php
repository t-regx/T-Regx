<?php
namespace TRegx\CleanRegex\Exception;

class NotReplacedException extends \Exception implements PatternException
{
    /** @var string */
    public $subject;

    public function __construct(string $message, string $subject)
    {
        parent::__construct($message);
        $this->subject = $subject;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }
}
