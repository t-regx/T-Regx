<?php
namespace Regex;

final class RecursionException extends MatchException
{
    public function __construct()
    {
        parent::__construct('Recursion depth limit exceeded when matching the subject.');
    }
}
