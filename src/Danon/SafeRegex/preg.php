<?php
namespace Danon\SafeRegex;

class preg
{
    public static function match($pattern, $subject, array &$matches = null, $flags = 0, $offset = 0)
    {
        $result = preg_match($pattern, $subject, $matches, $flags, $offset);
        self::validateResult($result, 'preg_match');
        return $result;
    }

    public static function match_all($pattern, $subject, array &$matches = null, $flags = PREG_PATTERN_ORDER, $offset = 0)
    {
        $result = preg_match_all($pattern, $subject, $matches, $flags, $offset);
        self::validateResult($result, 'preg_match_all');
        return $result;
    }

    public static function replace($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        $result = preg_replace($pattern, $replacement, $subject, $limit, $count);
        self::validateResult($result, 'preg_replace');
        return $result;
    }

    public static function replace_callback($pattern, callable $callback, $subject, $limit = -1, &$count = null)
    {
        $result = preg_replace_callback($pattern, $callback, $subject, $limit, $count);
        self::validateResult($result, 'preg_replace_callback');
        return $result;
    }

    public static function replace_callback_array($patterns_and_callbacks, $subject, $limit = -1, &$count)
    {
        $result = preg_replace_callback_array($patterns_and_callbacks, $subject, $limit, $count);
        self::validateResult($result, 'preg_replace_callback_array');
        return $result;
    }

    public static function filter($pattern, $replacement, $subject, $limit = -1, &$count = null)
    {
        $result = preg_filter($pattern, $replacement, $subject, $limit, $count);
        self::validateResult($result, 'preg_filter');
        return $result;
    }

    public static function split($pattern, $subject, $limit = -1, $flags = 0)
    {
        $result = preg_split($pattern, $subject, $limit, $flags);
        self::validateResult($result, 'preg_split');
        return $result;
    }

    public static function grep($pattern, array $input, $flags = 0)
    {
        $result = preg_grep($pattern, $input, $flags);
        self::validateResult($result, 'preg_grep');
        return $result;
    }

    public static function quote($string, $delimiter = null)
    {
        return preg_quote($string, $delimiter);
    }

    private static function validateResult($result, string $methodName)
    {
        if ($result === false) {
            throw new PregReturnException(preg::last_error(), $methodName);
        }
        self::validateLastPregError();
    }

    private static function validateLastPregError(): void
    {
        /** @link http://php.net/manual/en/pcre.constants.php */

        switch (preg_last_error()) {
            case PREG_NO_ERROR:
                return;

            case PREG_INTERNAL_ERROR:
                /**
                 * Returned by <b>preg_last_error</b> if there was an
                 * internal PCRE error.
                 */
                break;
            case PREG_BACKTRACK_LIMIT_ERROR:
                /**
                 * Returned by <b>preg_last_error</b> if backtrack limit was exhausted.
                 */
                break;

            case PREG_RECURSION_LIMIT_ERROR:
                /**
                 * Returned by <b>preg_last_error</b> if recursion limit was exhausted.
                 */
                break;

            case PREG_BAD_UTF8_ERROR:
                /**
                 * Returned by <b>preg_last_error</b> if the last error was
                 * caused by malformed UTF-8 data (only when running a regex in UTF-8 mode).
                 */
                break;

            case PREG_BAD_UTF8_OFFSET_ERROR:
                /**
                 * Returned by <b>preg_last_error</b> if the offset didn't
                 * correspond to the begin of a valid UTF-8 code point (only when running
                 * a regex in UTF-8 mode).
                 */
                break;
        }
    }

    public static function last_error()
    {
        return preg_last_error();
    }

    public static function last_error_constant(): string
    {
        return preg::error_constant(preg::last_error());
    }

    public static function error_constant($error): string
    {
        $constants = [
            PREG_NO_ERROR => 'PREG_NO_ERROR',
            PREG_BAD_UTF8_ERROR => 'PREG_BAD_UTF8_ERROR',
            PREG_INTERNAL_ERROR => 'PREG_INTERNAL_ERROR',
            PREG_BACKTRACK_LIMIT_ERROR => 'PREG_BACKTRACK_LIMIT_ERROR',
            PREG_RECURSION_LIMIT_ERROR => 'PREG_RECURSION_LIMIT_ERROR',
            PREG_BAD_UTF8_OFFSET_ERROR => 'PREG_BAD_UTF8_OFFSET_ERROR',
        ];

        if (array_key_exists($error, $constants)) {
            return $constants[$error];
        }

        return 'UNKNOWN_PREG_ERROR';
    }
}
