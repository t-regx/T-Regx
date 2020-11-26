<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

/**
 * When doing focus() replacement with references, it's impossible
 * to use PHP PCRE built in `preg_replace()`, but `focus()` does
 * have a method `withReferences()`, so we must provide a way to
 * replace references elements (`$1`, `\1`, `${1}`) in the string
 * with groups.
 */
class ReferencesReplacer
{
    public static function replace(string $subject, array $groups): string
    {
        return \preg_replace_callback(
            '/(?:[\\\\]{2}|\\\\(\d{1,2})|\$(?:(\d{1,2})|{(\d{1,2})}))/',
            static function (array $values) use ($subject, $groups) {
                if ($values[0] === '\\\\') {
                    return '\\';
                }
                if (\array_key_exists(3, $values)) {
                    $key = $values[3];
                } else if (\array_key_exists(2, $values)) {
                    $key = $values[2];
                } else {
                    $key = $values[1];
                }
                if (\array_key_exists((int)$key, $groups)) {
                    return $groups[(int)$key];
                }
                throw new InternalCleanRegexException();
            }, $subject);
    }
}
