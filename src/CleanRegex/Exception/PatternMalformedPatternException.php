<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\Exception\MalformedPatternException;

class PatternMalformedPatternException extends \Exception implements PatternException, MalformedPatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
