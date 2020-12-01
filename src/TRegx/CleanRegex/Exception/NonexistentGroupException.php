<?php
namespace TRegx\CleanRegex\Exception;

class NonexistentGroupException extends PatternException
{
    public function __construct($nameOrIndex)
    {
        parent::__construct("Nonexistent group: '$nameOrIndex'");
    }
}
