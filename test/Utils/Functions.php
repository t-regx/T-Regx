<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use Throwable;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;
use TRegx\CleanRegex\Internal\Type\ValueType;

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

    public static function pass($return = null): callable
    {
        return function () use ($return) {
            // Let PhpUnit know that missing assertions
            // are expected, not accidental
            Assert::assertTrue(true);
            return $return;
        };
    }

    public static function singleArg(callable $callable): callable
    {
        return function ($argument) use ($callable) { // ignore remaining arguments
            return $callable($argument);
        };
    }

    public static function charAt(int $position): callable
    {
        return static function (string $string) use ($position): string {
            if ($string === '') {
                throw new \AssertionError('Failed to retrieve a character from an empty string');
            }
            return $string[$position];
        };
    }

    public static function letters(): callable
    {
        return static function (string $string): array {
            return self::splitLetters($string);
        };
    }

    public static function lettersAsKeys(): callable
    {
        return function (string $value): array {
            return \array_flip(self::splitLetters($value));
        };
    }

    private static function splitLetters(string $string): array
    {
        return \array_filter(str_split($string), function (string $value) {
            return $value !== '';
        });
    }

    public static function wrap(): callable
    {
        return function ($value): array {
            return [$value];
        };
    }

    public static function peek(callable $peek, $return): callable
    {
        return function ($value) use ($peek, $return) {
            $peek($value);
            return $return;
        };
    }

    public static function surround(string $character): callable
    {
        return function (string $string) use ($character): string {
            return $character . $string . $character;
        };
    }

    public static function assertSame($expected, callable $mapper = null): callable
    {
        return function ($argument) use ($expected, $mapper): void {
            Assert::assertSame($expected, $mapper === null ? $argument : $mapper($argument));
        };
    }

    public static function property(string $property): callable
    {
        return function ($object) use ($property) {
            return $object->$property();
        };
    }

    public static function assertArgumentless(): callable
    {
        return function (...$args): void {
            Assert::assertEmpty($args, 'Failed to assert that function received 0 arguments');
        };
    }

    public static function mod(string $even, string $odd): callable
    {
        return function (int $integer) use ($even, $odd): string {
            return $integer % 2 === 0 ? $even : $odd;
        };
    }

    public static function collect(array &$collection = null, $return = null): callable
    {
        return function ($argument) use (&$collection, $return) {
            $collection[] = $argument;
            return $return;
        };
    }

    public static function secondArgument(): callable
    {
        return function ($irrelevant, $argument) {
            return $argument;
        };
    }

    public static function sum(): callable
    {
        return function (string $augend, string $addend): int {
            $base = new Base(10);
            $a = new StringNumeral($augend);
            $b = new StringNumeral($addend);
            return $a->asInt($base) + $b->asInt($base);
        };
    }

    public static function collectAsEntries(array &$entries = null): callable
    {
        $entries = [];
        return static function ($key, $value) use (&$entries) {
            $entries[$key] = $value;
        };
    }

    public static function asString(callable $function): callable
    {
        return static function ($argument) use ($function) {
            return (string)($function)($argument);
        };
    }

    public static function from(array $values): callable
    {
        return static function (int $index) use ($values) {
            return $values[$index];
        };
    }

    public static function arrayOfSize(int $size, array $append): callable
    {
        return function ($argument) use ($size, $append) {
            $array = array_fill(0, $size, $argument);
            foreach ($append as $tailItem) {
                $array[] = $tailItem;
            }
            return $array;
        };
    }

    public static function toMap(): callable
    {
        return static function ($argument) {
            if (is_int($argument) || is_string($argument)) {
                return [$argument => $argument];
            }
            $type = new ValueType($argument);
            throw new \AssertionError("Failed to represent argument of type $type as an array entry");
        };
    }

    public static function equals($expected): callable
    {
        return static function ($actual) use ($expected) {
            return $expected === $actual;
        };
    }

    public static function once($return = null): callable
    {
        $called = false;
        return static function () use ($return, &$called) {
            if ($called === false) {
                $called = true;
                return $return;
            }
            throw new \AssertionError("Failed to assert that callback was called only once");
        };
    }

    public static function duplicate(): callable
    {
        return function ($argument): array {
            return [$argument, $argument];
        };
    }
}
