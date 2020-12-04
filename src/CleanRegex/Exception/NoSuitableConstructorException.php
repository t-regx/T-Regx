<?php
namespace TRegx\CleanRegex\Exception;

class NoSuitableConstructorException extends PatternException
{
    public function __construct(string $className)
    {
        parent::__construct("Class '$className' doesn't have a constructor with supported signature");
    }
}
