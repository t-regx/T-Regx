<?php
namespace TRegx\CleanRegex\Exception\Preg;

class PatternMatchesException extends PregException
{
    /** @var int */
    private $lastError;

    public function __construct(int $lastError)
    {
        parent::__construct("Last error code: $lastError");
        $this->lastError = $lastError;
    }
}
