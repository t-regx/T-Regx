<?php
namespace TRegx\CleanRegex\Exception;

class MalformedPcreTemplateException extends PatternMalformedPatternException
{
    public function __construct(string $message)
    {
        parent::__construct("PCRE-compatible template is malformed, $message");
    }
}
