<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Replace\FocusReplacePattern;

/**
 * When doing {@see CompositeReplacePattern::focus} replacement with
 * references, it's impossible to use PHP PCRE built-in {@see preg_replace},
 * but {@see CompositeReplacePattern::focus} does have a method
 * {@see FocusReplacePattern::withReferences}. Hence, we must provide
 * a way to replace references elements (`$1`, `\1`, `${1}`) in the
 * string with groups.
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
                return ''; // preg_replace() dictates a contract, where missing group is represented with an empty string
            }, $subject);
    }
}
