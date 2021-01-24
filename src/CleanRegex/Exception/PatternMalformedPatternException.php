<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\SafeRegex\Exception\MalformedPatternException;

class PatternMalformedPatternException extends \Exception implements PatternException, MalformedPatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
