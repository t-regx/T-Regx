<?php
namespace Regex;

final class UnicodeException extends RegexException
{
    public function __construct()
    {
        parent::__construct('Malformed unicode subject.');
    }
}
