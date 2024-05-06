<?php
namespace Regex;

final class PcreException extends MatchException
{
    public function __construct(string $reason)
    {
        parent::__construct("Failed to match the subject, due to $reason.");
    }
}
