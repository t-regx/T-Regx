<?php
namespace Regex;

final class PcreException extends MatchException
{
    public function __construct()
    {
        parent::__construct("Failed to match the subject, due to pcre internal error.");
    }
}
