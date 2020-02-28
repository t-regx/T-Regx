<?php
namespace TRegx\CleanRegex\Exception;

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
