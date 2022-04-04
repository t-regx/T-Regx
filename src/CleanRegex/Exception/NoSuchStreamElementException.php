<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Message\Message;

class NoSuchStreamElementException extends \Exception implements PatternException
{
    public function __construct(Message $message)
    {
        parent::__construct($message->getMessage());
    }
}
