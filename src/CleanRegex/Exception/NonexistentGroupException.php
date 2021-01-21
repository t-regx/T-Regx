<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class NonexistentGroupException extends \Exception implements PatternException
{
    public function __construct($nameOrIndex)
    {
        parent::__construct("Nonexistent group: " . Type::group($nameOrIndex));
    }
}
