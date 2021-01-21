<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class NoSuchElementFluentException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function withMessage(NotMatchedMessage $message): self
    {
        return new self($message->getMessage());
    }
}
