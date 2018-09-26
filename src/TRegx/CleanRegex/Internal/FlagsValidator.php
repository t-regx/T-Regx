<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\CleanRegex\FlagNotAllowedException;
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
     * @return void
     * @throws FlagNotAllowedException
     */
    public function validate(string $flags): void
    {
        if (empty($flags)) {
            return;
        }
        $this->validateFlags($flags);
    }

    private function validateFlags(string $flags): void
    {
        foreach (str_split($flags) as $flag) {
            if (!$this->isAllowed($flag)) {
                throw new FlagNotAllowedException($flag);
            }
        }
    }

    private function isAllowed(string $character): bool
    {
        return in_array($character, self::$flags, true);
    }
}
