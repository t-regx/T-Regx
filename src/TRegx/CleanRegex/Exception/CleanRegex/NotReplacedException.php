<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class NotReplacedException extends CleanRegexException
{
    public function __construct()
    {
        parent::__construct("Replacements were supposed to be performed, but subject doesn't match the pattern");
    }
}
