<?php
namespace TRegx\SafeRegex;

use TRegx\SafeRegex\Constants\PregConstants;
use TRegx\SafeRegex\Constants\PregMessages;
use TRegx\SafeRegex\Exception\InvalidReturnValueException;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Guard\GuardedExecution as Guard;
use TRegx\SafeRegex\Guard\Strategy\PregFilterSuspectedReturnStrategy;
use TRegx\SafeRegex\Guard\Strategy\PregReplaceSuspectedReturnStrategy;
use TRegx\SafeRegex\Guard\Strategy\SilencedSuspectedReturnStrategy;
use TRegx\SafeRegex\Internal\Bug;

class preg
{
    /**
     * Perform a regular expression match
     * @link https://php.net/manual/en/function.preg-match.php
     *
     * @return int Returns 1 if the pattern matches given subject, 0 if it does not
     *
     * @param-out array $matches
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @throws PregException
     */
    public static function match(string $pattern, string $subject, array &$matches = null, int $flags = 0, int $offset = 0): int
    {
        return Guard::invoke('preg_match', static function () use ($offset, $flags, &$matches, $subject, $pattern) {
            return @\preg_match(Bug::fix($pattern), $subject, $matches, $flags, $offset) ? 1 : 0;
        });
    }

    /**
     * Perform a global regular expression match
     * @link https://php.net/manual/en/function.preg-match-all.php
     *
     * @return int Number of full pattern matches (which might be zero)
     *
     * @param-out array $matches
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @throws PregException
     */
    public static function match_all(string $pattern, string $subject, array &$matches = null, $flags = \PREG_PATTERN_ORDER, int $offset = 0): int
    {
        return Guard::invoke('preg_match_all', static function () use ($offset, $flags, &$matches, $subject, $pattern) {
            return @\preg_match_all(Bug::fix($pattern), $subject, $matches, $flags, $offset);
        });
    }

    /**
     * Perform a regular expression search and replace
     * @link https://php.net/manual/en/function.preg-replace.php
     *
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @return string|string[]
     *
     * @param-out int $count
     *
     * @template T of string|string[]
     * @psalm-param T $subject
     * @psalm-return T
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @throws PregException
     */
    public static function replace($pattern, $replacement, $subject, int $limit = -1, int &$count = null)
    {
        return Guard::invoke('preg_replace', static function () use ($limit, $subject, $replacement, $pattern, &$count) {
            return @\preg_replace(Bug::fix($pattern), $replacement, $subject, $limit, $count);
        }, new PregReplaceSuspectedReturnStrategy($subject));
    }

    /**
     * Perform a regular expression search and replace using a callback
     * @link https://php.net/manual/en/function.preg-replace-callback.php
     *
     * @param string|string[] $pattern
     * @param string|string[] $subject
     * @return string|string[]
     *
     * @param-out int $count
     *
     * @template T of string|string[]
     * @psalm-param T $subject
     * @psalm-return T
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @throws PregException
     */
    public static function replace_callback($pattern, callable $callback, $subject, int $limit = -1, int &$count = null)
    {
        return Guard::invoke('preg_replace_callback', static function () use ($pattern, $limit, $subject, $callback, &$count) {
            return @\preg_replace_callback(Bug::fix($pattern), self::decorateCallback('preg_replace_callback', $callback), $subject, $limit, $count);
        });
    }

    /**
     * Perform a regular expression search and replace using callbacks
     * @link https://php.net/manual/en/function.preg-replace-callback-array.php
     *
     * @param array<string,callable> $patterns_and_callbacks An associative array mapping patterns (keys) to callbacks (values)
     * @param string|string[] $subject
     * @return string|string[]
     *
     * @param-out int $count
     *
     * @template T of string|string[]
     * @psalm-param T $subject
     * @psalm-return T
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @throws PregException
     */
    public static function replace_callback_array($patterns_and_callbacks, $subject, int $limit = -1, int &$count = null)
    {
        return Guard::invoke('preg_replace_callback_array', static function () use ($patterns_and_callbacks, $subject, $limit, &$count) {
            return @\preg_replace_callback_array(\array_map(static function ($callback) {
                return self::decorateCallback('preg_replace_callback_array', $callback);
            }, Bug::fixArrayKeys($patterns_and_callbacks)), $subject, $limit, $count);
        });
    }

    private static function decorateCallback(string $methodName, $callback)
    {
        if (!\is_callable($callback)) {
            throw new \InvalidArgumentException("Invalid callback passed to '$methodName'");
        }
        return static function (...$args) use ($callback, $methodName) {
            $value = $callback(...$args);
            if (!\is_object($value) || \method_exists($value, '__toString')) {
                return $value;
            }
            throw new InvalidReturnValueException($methodName, \gettype($value));
        };
    }

    /**
     * Perform a regular expression search and replace
     * @link https://php.net/manual/en/function.preg-filter.php
     *
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @return string|string[]
     *
     * @param-out int $count
     *
     * @template T of string|string[]
     * @psalm-param T $subject
     * @psalm-return T
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @throws PregException
     */
    public static function filter($pattern, $replacement, $subject, int $limit = -1, int &$count = null)
    {
        return Guard::invoke('preg_filter', static function () use ($pattern, $replacement, $subject, $limit, &$count) {
            return @\preg_filter(Bug::fix($pattern), $replacement, $subject, $limit, $count);
        }, new PregFilterSuspectedReturnStrategy($subject));
    }

    /**
     * Split string by a regular expression
     * @link https://php.net/manual/en/function.preg-split.php
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @return string[]|array[]
     *
     * @throws PregException
     */
    public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0)
    {
        return Guard::invoke('preg_split', static function () use ($pattern, $subject, $limit, $flags) {
            return @\preg_split(Bug::fix($pattern), $subject, $limit, $flags);
        });
    }

    /**
     * Return array entries that match the pattern
     * @link https://php.net/manual/en/function.preg-grep.php
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @throws PregException
     */
    public static function grep(string $pattern, array $input, int $flags = 0): array
    {
        $input = \array_filter($input, function ($value) {
            return !\is_object($value) || \method_exists($value, '__toString');
        });
        return Guard::invoke('preg_grep', static function () use ($flags, $input, $pattern) {
            return @\preg_grep(Bug::fix($pattern), $input, $flags);
        }, new SilencedSuspectedReturnStrategy());
    }

    /**
     * @psalm-pure Output is only dependent on input parameters values
     */
    public static function grep_keys(string $pattern, array $input, int $flags = 0): array
    {
        return \array_intersect_key($input, \array_flip(self::grep($pattern, \array_keys($input), $flags)));
    }

    /**
     * Quote regular expression characters
     * @link https://php.net/manual/en/function.preg-quote.php
     *
     * @psalm-pure Output is only dependent on input parameters values
     */
    public static function quote(string $string, ?string $delimiter = null): string
    {
        if (!\is_null($delimiter) && \strlen($delimiter) !== 1) {
            throw new \InvalidArgumentException('Delimiter must be one alpha-numeric character');
        }
        if (\preg_quote('#', $delimiter) === '#') {
            return \str_replace('#', '\#', \preg_quote($string, $delimiter));
        }
        return \preg_quote($string, $delimiter);
    }

    /**
     * Returns the error code of the last PCRE regex execution
     * @link https://php.net/manual/en/function.preg-last-error.php
     *
     * @return int one of the following constants (explained on their own page):
     * <b>PREG_NO_ERROR</b>
     * <b>PREG_INTERNAL_ERROR</b>
     * <b>PREG_BACKTRACK_LIMIT_ERROR</b> (see also pcre.backtrack_limit)
     * <b>PREG_RECURSION_LIMIT_ERROR</b> (see also pcre.recursion_limit)
     * <b>PREG_BAD_UTF8_ERROR</b>
     * <b>PREG_BAD_UTF8_OFFSET_ERROR</b> (since PHP 5.3.0)
     */
    public static function last_error(): int
    {
        /**
         * Please, keep in mind that calling `preg::last_error()`, by design, is useless,
         * because `preg::*()` functions never fail with `false`, `null` or by
         * using `preg_last_error()` method.
         *
         * So in normal situations, this function will always return `PREG_NO_ERROR`.
         */

        // @codeCoverageIgnoreStart
        return \preg_last_error();
        // @codeCoverageIgnoreEnd
    }

    public static function last_error_constant(): string
    {
        return (new PregConstants())->getConstant(\preg_last_error());
    }

    public static function last_error_msg(): string
    {
        return (new PregMessages())->getConstant(\preg_last_error());
    }
}
