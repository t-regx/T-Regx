<?php
namespace Regex;

final class BacktrackException extends MatchException
{
    public function __construct()
    {
        parent::__construct('Catastrophic backtracking occurred when matching the subject.');
    }
}
