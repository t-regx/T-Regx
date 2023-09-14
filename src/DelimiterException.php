<?php
namespace Regex;

final class DelimiterException extends RegexException
{
    public function __construct()
    {
        parent::__construct('Failed to delimiter the given regular expression.');
    }
}
