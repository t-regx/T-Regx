<?php
namespace TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Subject;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;
use function sprintf;

class FirstGroupSubjectMessage implements NotMatchedMessage
{
    private const MESSAGE = "Expected to get group '%s' from first match, but subject was not matched at all";

    /** @var string */
    private $message;

    public function __construct($nameOrIndex)
    {
        $this->message = sprintf(self::MESSAGE, $nameOrIndex);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
