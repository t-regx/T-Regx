<?php
namespace TRegx\CleanRegex\Exception;

class NotReplacedException extends PatternException
{
    public function __construct()
    {
        parent::__construct("Replacements were supposed to be performed, but subject doesn't match the pattern");
    }
}
