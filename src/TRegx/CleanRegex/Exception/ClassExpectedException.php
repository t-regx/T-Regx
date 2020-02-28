<?php
namespace TRegx\CleanRegex\Exception;

class ClassExpectedException extends PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function notFound(string $className): ClassExpectedException
    {
        return new ClassExpectedException("Class '$className' does not exists");
    }

    public static function notThrowable(string $className)
    {
        return new ClassExpectedException("Class '$className' is not throwable");
    }

    public static function isInterface(string $className)
    {
        return new ClassExpectedException("'$className' is not a class, but an interface");
    }

    public static function isAbstract(string $className)
    {
        return new ClassExpectedException("Class '$className' is an abstract class");
    }
}
