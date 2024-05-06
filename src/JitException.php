<?php
namespace Regex;

final class JitException extends MatchException
{
    public function __construct()
    {
        parent::__construct('Just-in-time compilation stack limit exceeded when executing the pattern.');
    }
}
