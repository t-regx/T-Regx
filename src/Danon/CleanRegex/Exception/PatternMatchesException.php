<?php
namespace Danon\CleanRegex\Exception;

class PatternMatchesException extends \Exception
{
    /** @var int */
    private $lastError;

    public function __construct(int $lastError)
    {
        $this->lastError = $lastError;
    }
}
