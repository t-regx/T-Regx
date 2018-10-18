<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\CleanRegex\FlagNotAllowedException;
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
        $invalid = $this->getInvalidFlags($flags);
        if (count($invalid) === 1) {
            throw FlagNotAllowedException::forOne(reset($invalid));
        }
        if (count($invalid) > 1) {
            throw FlagNotAllowedException::forMany($invalid);
        }
    }

    private function getInvalidFlags(string $flags): array
    {
        return array_diff($this->toArray($flags), self::$flags);
    }

    private function toArray(string $flags): array
    {
        if (empty($flags)) {
            return [];
        }
        return str_split($flags);
    }
}
