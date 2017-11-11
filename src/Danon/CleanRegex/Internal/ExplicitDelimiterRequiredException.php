<?php
namespace Danon\CleanRegex\Internal;

use Danon\CleanRegex\Exception\CleanRegex\CleanRegexException;

class ExplicitDelimiterRequiredException extends CleanRegexException
{
    public function __construct(string $pattern)
    {
        parent::__construct($this->getExceptionMessage($pattern));
    }

    private function getExceptionMessage($pattern)
    {
        return "Unfortunately, CleanRegex couldn't find any indistinct delimiter to match your pattern \"$pattern\". " .
            "Please specify the delimiter explicitly, and escape the delimiter character inside your pattern.";
    }
}
