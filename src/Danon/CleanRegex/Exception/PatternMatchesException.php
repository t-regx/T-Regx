<?php
namespace Danon\CleanRegex\Exception;

class PatternMatchesException extends \Exception
{
    /** @var int */
    private $lastError;

    public function __construct(int $lastError)
    {
        parent::__construct("Last error code: $lastError");
        $this->lastError = $lastError;
    }
}
