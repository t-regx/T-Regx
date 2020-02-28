<?php
namespace TRegx\CleanRegex\Exception;

class ExplicitDelimiterRequiredException extends PatternException
{
    public function __construct(string $pattern)
    {
        parent::__construct($this->getExceptionMessage($pattern));
    }

    private function getExceptionMessage($pattern): string
    {
        return "Unfortunately, CleanRegex couldn't find any indistinct delimiter to match your pattern \"$pattern\". " .
            'Please specify the delimiter explicitly, and escape the delimiter character inside your pattern.';
    }
}
