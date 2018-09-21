<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class NoSuitableConstructorException extends CleanRegexException
{
    public function __construct(string $className)
    {
        parent::__construct("Class '$className' doesn't have a constructor with supported signature");
    }
}
