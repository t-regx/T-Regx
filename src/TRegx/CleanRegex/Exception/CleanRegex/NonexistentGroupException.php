<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class NonexistentGroupException extends CleanRegexException
{
    /**
     * @param string|int $nameOrIndex
     */
    public function __construct($nameOrIndex)
    {
        parent::__construct("Nonexistent group: '$nameOrIndex'");
    }
}
