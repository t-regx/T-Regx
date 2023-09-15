<?php
namespace Regex;

final class NoMatchException extends RegexException
{
    public function __construct()
    {
        parent::__construct('Failed to match the subject.');
    }
}
