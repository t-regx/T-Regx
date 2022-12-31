<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use Throwable;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;

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

    public static function charAt(int $position): callable
    {
        return static function (string $string) use ($position): string {
            if ($string === '') {
                throw new \AssertionError('Failed to retrieve a character from an empty string');
            }
            return $string[$position];
        };
    }

    public static function substring(int $start, int $length): callable
    {
        return function (string $argument) use ($start, $length): string {
            return \subStr($argument, $start, $length);
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

    public static function lettersAsEntries(): callable
    {
        return function (string $value): array {
            $letters = self::splitLetters($value);
            return \array_combine($letters, $letters);
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

    public static function wrapKeySequential(int $start = 0): callable
    {
        $index = $start;
        return function ($value) use (&$index): array {
            return [$index++ => $value];
        };
    }

    public static function assertSame($expected, callable $mapper = null): callable
    {
        return function ($argument) use ($expected, $mapper): void {
            Assert::assertSame($expected, $mapper === null ? $argument : $mapper($argument));
        };
    }

    public static function assertArguments(...$expected): callable
    {
        return function (...$arguments) use ($expected): void {
            Assert::assertSame($expected, $arguments);
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
        return function (...$args) {
            Assert::assertEmpty($args, 'Failed to assert that function received 0 arguments');
        };
    }

    public static function out(&$argument, $return = null): callable
    {
        $wasCaptured = false;
        return function ($capturedArgument) use (&$wasCaptured, &$argument, $return) {
            if ($wasCaptured) {
                return $return;
            }
            $argument = $capturedArgument;
            $wasCaptured = true;
            return $return;
        };
    }

    public static function outLast(&$argument, $return = null): callable
    {
        return function ($capturedArgument) use (&$argument, $return) {
            $argument = $capturedArgument;
            return $return;
        };
    }

    public static function collect(array &$collection = null, $return = null): callable
    {
        return function ($argument) use (&$collection, $return) {
            $collection[] = $argument;
            return $return;
        };
    }

    public static function collecting(?array &$arguments, callable $return = null): callable
    {
        return function ($argument) use (&$arguments, $return) {
            $arguments[] = $argument;
            if ($return === null) {
                return null;
            }
            return $return($argument);
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

    public static function collectEntries(array &$entries = null): callable
    {
        $entries = [];
        return static function ($value, $key) use (&$entries) {
            $entries[] = [$value, $key];
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

    public static function equals($expected): callable
    {
        return static function ($actual) use ($expected) {
            return $expected === $actual;
        };
    }

    public static function notEquals(string $rejected): callable
    {
        return static function ($actual) use ($rejected) {
            return $rejected !== $actual;
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

    public static function eachNext(array $values): callable
    {
        return function () use (&$values) {
            $value = current($values);
            next($values);
            return $value;
        };
    }

    public static function oneOf(array $elements): callable
    {
        return function ($argument) use ($elements): bool {
            return \in_array($argument, $elements, true);
        };
    }

    public static function toInt(): callable
    {
        return function (string $argument): int {
            $numeral = new StringNumeral($argument);
            return $numeral->asInt(new Base(10));
        };
    }

    public static function toUpper(): callable
    {
        return function (string $argument): string {
            return \strToUpper($argument);
        };
    }

    public static function skipArgument(callable $function): callable
    {
        return function ($argument, ...$arguments) use ($function) {
            return $function(...$arguments);
        };
    }

    public static function ignore(): callable
    {
        return static function (): void {
        };
    }
}
