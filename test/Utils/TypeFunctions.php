<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\Details\Detail;

class TypeFunctions
{
    public static function assertTypeDetail($return = null): callable
    {
        return function (...$arguments) use ($return) {
            if (\count($arguments) !== 1) {
                throw new \AssertionError("Failed to assert that callback recieved a single argument");
            }
            [$argument] = $arguments;
            if (!$argument instanceof Detail) {
                throw new \AssertionError("Failed to assert that callback recieved argument of type Detail");
            }
            return $return;
        };
    }

    public static function assertTypeString($return = null): callable
    {
        return function (...$arguments) use ($return) {
            if (\count($arguments) !== 1) {
                throw new \AssertionError("Failed to assert that callback recieved a single argument");
            }
            [$argument] = $arguments;
            if (!\is_string($argument)) {
                throw new \AssertionError("Failed to assert that callback recieved argument of type string");
            }
            return $return;
        };
    }

    public static function assertTypeStringString(): callable
    {
        return function (...$arguments): void {
            if (\count($arguments) !== 2) {
                throw new \AssertionError("Failed to assert that callback recieved two arguments");
            }
            [$argument1, $argument2] = $arguments;
            if (!\is_string($argument1) || !\is_string($argument2)) {
                throw new \AssertionError("Failed to assert that callback recieved argument of type string");
            }
        };
    }
}
