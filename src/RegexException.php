<?php
namespace Regex;

abstract class RegexException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
