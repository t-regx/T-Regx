<?php
namespace TRegx\CleanRegex\Exception;

class NotReplacedException extends PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
