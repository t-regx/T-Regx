<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class FlagNotAllowedException extends CleanRegexException
{
    public function __construct(string $flag)
    {
        parent::__construct("Regular expression flag '$flag' is not allowed");
    }
}
