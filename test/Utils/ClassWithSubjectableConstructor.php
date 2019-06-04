<?php
namespace Test\Utils;

use Exception;
use TRegx\CleanRegex\Internal\Subjectable;

class ClassWithSubjectableConstructor extends Exception
{
    public function __construct(string $message, Subjectable $subjectable)
    {
        parent::__construct($message);
    }
}
