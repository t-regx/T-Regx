<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\DuplicateFlagsException;
use TRegx\CleanRegex\Exception\FlagNotAllowedException;
use TRegx\SafeRegex\Guard\Arrays;

class FlagsValidator
{
    private static $flags = [
        'i', // PCRE_CASELESS
        'm', // PCRE_MULTILINE
        'x', // PCRE_EXTENDED
        's', // PCRE_DOTALL
        'u', // PCRE_UNICODE

        'U', // PCRE_UNGREEDY
        'X', // PCRE_EXTRA
        'A', // PCRE_ANCHORED
        'J', // PCRE_INFO_JCHANGED
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
        if ($flags === '') return;
        $this->validateInvalidFlags($flags);
        $this->validateDuplicatedFlags($flags);
    }

    private function validateInvalidFlags(string $flags): void
    {
        $invalid = $this->getInvalidFlags($flags);
        if (\count($invalid) === 1) {
            throw FlagNotAllowedException::forOne(\reset($invalid));
        }
        if (\count($invalid) > 1) {
            throw FlagNotAllowedException::forMany($invalid);
        }
    }

    private function getInvalidFlags(string $flags): array
    {
        return \array_diff(\str_split($flags), self::$flags);
    }

    private function validateDuplicatedFlags(string $flags): void
    {
        $duplicates = Arrays::getDuplicates(\str_split($flags));
        if (!empty($duplicates)) {
            throw DuplicateFlagsException::forFlag($duplicates[0], $flags);
        }
    }
}
