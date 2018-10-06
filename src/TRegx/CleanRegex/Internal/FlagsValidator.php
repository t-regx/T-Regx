<?php
namespace TRegx\CleanRegex\Internal;

use function in_array;
use function str_split;

class FlagsValidator
{
    private static $flags = [
        'i', // PCRE_CASELESS
        'm', // PCRE_MULTILINE
        'x', // PCRE_EXTENDED
        's', // PCRE_DOTALL

        'U', // PCRE_UNGREEDY
        'X', // PCRE_EXTRA
        'A', // PCRE_ANCHORED
        'D', // PCRE_DOLLAR_ENDONLY
        'S', // Studying a pattern, before executing
    ];

    /**
     * @param string $flags
     * @return bool
     */
    public function isValid(string $flags): bool
    {
        if (empty($flags)) {
            return true;
        }
        return $this->areFlagsValid($flags);
    }

    private function areFlagsValid(string $flags): bool
    {
        foreach (str_split($flags) as $flag) {
            if (!$this->isAllowed($flag)) {
                return false;
            }
        }
        return true;
    }

    private function isAllowed(string $character): bool
    {
        return in_array($character, self::$flags, true);
    }
}
