<?php
namespace TRegx\SafeRegex;

use InvalidArgumentException;
use TRegx\SafeRegex\Exception\InvalidReturnValueException;
use TRegx\SafeRegex\Internal\Bug;
use TRegx\SafeRegex\Internal\Constants\PregConstants;
use TRegx\SafeRegex\Internal\Constants\PregMessages;
use TRegx\SafeRegex\Internal\Guard\GuardedExecution as Guard;
use TRegx\SafeRegex\Internal\Guard\Strategy\PregFilterSuspectedReturnStrategy;
use TRegx\SafeRegex\Internal\Guard\Strategy\PregReplaceSuspectedReturnStrategy;
use TRegx\SafeRegex\Internal\Guard\Strategy\SilencedSuspectedReturnStrategy;

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
     */
    public static function match(string $pattern, string $subject, array &$matches = null, int $flags = 0, int $offset = 0): int
    {
        $pr = Bug::fix($pattern);
        return Guard::invoke('preg_match', $pr, static function () use ($pr, $subject, &$matches, $flags, $offset) {
            return @\preg_match($pr, $subject, $matches, $flags, self::offset($subject, $offset)) ? 1 : 0;
        });
    }

    private static function offset(string $subject, int $offset): int
    {
        if ($offset < 0) {
            throw new InvalidArgumentException("Negative offset: $offset");
        }
        $length = \strLen($subject);
        if ($length < $offset) {
            throw new InvalidArgumentException("Overflowing offset: $offset, while subject has length: $length (bytes)");
        }
        return $offset;
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
     */
    public static function match_all(string $pattern, string $subject, array &$matches = null, $flags = \PREG_PATTERN_ORDER, int $offset = 0): int
    {
        $pr = Bug::fix($pattern);
        return Guard::invoke('preg_match_all', $pr, static function () use ($pr, $subject, &$matches, $flags, $offset) {
            return @\preg_match_all($pr, $subject, $matches, $flags, self::offset($subject, $offset));
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
     */
    public static function replace($pattern, $replacement, $subject, int $limit = -1, int &$count = null)
    {
        $pr = Bug::fix($pattern);
        return Guard::invoke('preg_replace', $pr, static function () use ($pr, $replacement, $subject, $limit, &$count) {
            return @\preg_replace($pr, $replacement, $subject, $limit, $count);
        }, new PregReplaceSuspectedReturnStrategy($subject));
    }

    /**
     * Perform a regular expression search and replace using a callback
     * @link https://php.net/manual/en/function.preg-replace-callback.php
     *
     * @param string|string[] $pattern
     * @param string|string[] $subject
     * @param int|null $flags
     * @return string|string[]
     *
     * @param-out int $count
     *
     * @template T of string|string[]
     * @psalm-param T $subject
     * @psalm-return T
     *
     * @psalm-pure Output is only dependent on input parameters values
     */
    public static function replace_callback($pattern, callable $callback, $subject, int $limit = -1, int &$count = null, int $flags = 0)
    {
        $pr = Bug::fix($pattern);
        return Guard::invoke('preg_replace_callback', $pr, static function () use ($pr, $callback, $subject, $limit, &$count, $flags) {
            if ($flags === 0) {
                return @\preg_replace_callback($pr, self::decorateCallback('preg_replace_callback', $pr, $callback), $subject, $limit, $count);
            }
            return @\preg_replace_callback($pr, self::decorateCallback('preg_replace_callback', $pr, $callback), $subject, $limit, $count, $flags);
        });
    }

    /**
     * Perform a regular expression search and replace using callbacks
     * @link https://php.net/manual/en/function.preg-replace-callback-array.php
     *
     * @param array<string,callable> $patterns_and_callbacks An associative array mapping patterns (keys) to callbacks (values)
     * @param string|string[] $subject
     * @param int $limit
     * @param int|null $count
     * @return string|string[]
     *
     * @param-out int $count
     *
     * @template T of string|string[]
     * @psalm-param T $subject
     * @psalm-return T
     *
     * @psalm-pure Output is only dependent on input parameters values
     */
    public static function replace_callback_array(array $patterns_and_callbacks, $subject, int $limit = -1, int &$count = null)
    {
        $prs = Bug::fixArrayKeys($patterns_and_callbacks);
        return Guard::invoke('preg_replace_callback_array', \array_keys($prs), static function () use ($prs, $subject, $limit, &$count) {
            return @\preg_replace_callback_array(\array_map(static function ($callback) use ($prs) {
                return self::decorateCallback('preg_replace_callback_array', \array_keys($prs), $callback);
            }, $prs), $subject, $limit, $count);
        });
    }

    private static function decorateCallback(string $methodName, $pattern, $callback): callable
    {
        if (!\is_callable($callback)) {
            throw new InvalidArgumentException("Invalid callback passed to $methodName()");
        }
        return static function (...$args) use ($methodName, $pattern, $callback) {
            $value = $callback(...$args);
            if (!\is_object($value) || \method_exists($value, '__toString')) {
                return $value;
            }
            throw new InvalidReturnValueException($pattern, $methodName, \gettype($value));
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
     */
    public static function filter($pattern, $replacement, $subject, int $limit = -1, int &$count = null)
    {
        $pr = Bug::fix($pattern);
        return Guard::invoke('preg_filter', $pr, static function () use ($pr, $replacement, $subject, $limit, &$count) {
            return @\preg_filter($pr, $replacement, $subject, $limit, $count);
        }, new PregFilterSuspectedReturnStrategy($subject));
    }

    /**
     * Split string by a regular expression
     * @link https://php.net/manual/en/function.preg-split.php
     *
     * @psalm-pure Output is only dependent on input parameters values
     *
     * @return string[]|array[]
     */
    public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0): array
    {
        $pr = Bug::fix($pattern);
        return Guard::invoke('preg_split', $pr, static function () use ($pr, $subject, $limit, $flags) {
            return @\preg_split($pr, $subject, $limit, $flags);
        });
    }

    /**
     * Return array entries that match the pattern
     * @link https://php.net/manual/en/function.preg-grep.php
     *
     * @psalm-pure Output is only dependent on input parameters values
     */
    public static function grep(string $pattern, array $input, int $flags = 0): array
    {
        $input = \array_filter($input, static function ($value): bool {
            return !\is_object($value) || \method_exists($value, '__toString');
        });
        $pr = Bug::fix($pattern);
        return Guard::invoke('preg_grep', $pr, static function () use ($pr, $input, $flags) {
            return @\preg_grep($pr, $input, $flags);
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
    public static function quote(string $string, string $delimiter = null): string
    {
        if (!\is_null($delimiter) && \strLen($delimiter) !== 1) {
            throw new InvalidArgumentException('Delimiter must be one alpha-numeric character');
        }
        if (\preg_quote('#', $delimiter) === '#') {
            return \str_replace('#', '\#', \preg_quote($string, $delimiter));
        }
        return \preg_quote($string, $delimiter);
    }

    public static function unquote(string $string): string
    {
        return self::unquoteStringWithCharacters($string, [
            '.', '\\', '+', '*', '?', '[', ']', '^', '$', '(', ')',
            '{', '}', '=', '!', '<', '>', '|', ':', '-', '#'
        ]);
    }

    private static function unquoteStringWithCharacters(string $string, array $specialCharacters): string
    {
        return \strtr($string, \array_combine(\array_map(static function (string $char): string {
            return "\\$char";
        }, $specialCharacters), $specialCharacters));
    }

    /**
     * Returns the error code of the last PCRE regex execution
     *
     * Please, keep in mind that calling {@see preg::last_error}, by design, is useless,
     * because {@see preg} methods never fail with {@see false}, {@see null} or set
     * error code for the purpose of {@see preg_last_error}.
     *
     * So in normal situations, this function will always return {@see PREG_NO_ERROR}.
     *
     * @link https://php.net/manual/en/function.preg-last-error.php
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
