<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use Throwable;
use TRegx\CleanRegex\Match\Details\Detail;

class Functions
{
    public static function identity(): callable
    {
        return function ($arg) {
            return $arg;
        };
    }

    public static function constant($value): callable
    {
        return function () use ($value) {
            return $value;
        };
    }

    public static function throws(Throwable $throwable): callable
    {
        return function () use ($throwable) {
            throw $throwable;
        };
    }

    public static function fail(string $message = null): callable
    {
        return function () use ($message) {
            Assert::fail($message ?? 'Failed to assert that callback is not invoked');
        };
    }

    public static function pass(): callable
    {
        return function () {
            // Let PhpUnit know that missing assertions
            // are expected, not accidental
            Assert::assertTrue(true);
        };
    }

    public static function singleArg(callable $callable): callable
    {
        return function ($argument) use ($callable) { // ignore remaining arguments
            return $callable($argument);
        };
    }

    public static function stringIndex(int $index): callable
    {
        return function (string $fucker) use ($index) {
            return $fucker[$index];
        };
    }

    public static function equals(string $detail): callable
    {
        return function (Detail $match) use ($detail) {
            return "$match" === $detail;
        };
    }

    public static function notEquals(string $detail): callable
    {
        return function (Detail $match) use ($detail) {
            return "$match" !== $detail;
        };
    }

    public static function collecting(array &$details): callable
    {
        return function (Detail $detail) use (&$details) {
            $details[] = $detail->text();
            return true;
        };
    }

    public static function charAt(int $position): callable
    {
        return static function (string $string) use ($position): string {
            if (empty($string)) {
                throw new \AssertionError("Empty string");
            }
            return $string[$position];
        };
    }
}
