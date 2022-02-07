<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use Throwable;
use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;
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

    public static function oneOf(array $haystack): callable
    {
        return function (Detail $match) use ($haystack) {
            return \in_array("$match", $haystack);
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

    public static function peek(callable $peek, callable $callback): callable
    {
        return function ($value) use ($peek, $callback) {
            $peek($value);
            return $callback($value);
        };
    }

    public static function surround(string $character): callable
    {
        return function (string $string) use ($character): string {
            return $character . $string . $character;
        };
    }

    public static function json(): callable
    {
        return function ($value): string {
            return \json_encode($value, \JSON_UNESCAPED_SLASHES);
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

    public static function argumentless(): callable
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

    public static function collect(&$collect, $return = null): callable
    {
        return function ($argument) use (&$collect, $return) {
            $collect = $argument;
            return $return;
        };
    }

    public static function asString(): callable
    {
        return function (string $argument): string {
            return $argument;
        };
    }

    public static function asStringSecond(): callable
    {
        return function ($irrelevant, string $argument): string {
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
}
