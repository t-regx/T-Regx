<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use Throwable;

class InvalidPatternException extends CleanRegexException
{
    public function __construct(string $message, Throwable $previous)
    {
        parent::__construct($message, 0, $previous);
    }
}
