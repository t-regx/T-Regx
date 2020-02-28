<?php
namespace TRegx\CleanRegex\Exception;

class NonexistentGroupException extends PatternException
{
    /**
     * @param string|int $nameOrIndex
     */
    public function __construct($nameOrIndex)
    {
        parent::__construct("Nonexistent group: '$nameOrIndex'");
    }
}
