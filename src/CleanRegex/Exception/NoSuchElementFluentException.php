<?php
namespace TRegx\CleanRegex\Exception;

class NoSuchElementFluentException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
