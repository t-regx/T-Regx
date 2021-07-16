<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupFormat;

class NonexistentGroupException extends \Exception implements PatternException
{
    public function __construct($nameOrIndex)
    {
        parent::__construct("Nonexistent group: " . GroupFormat::group($nameOrIndex));
    }
}
