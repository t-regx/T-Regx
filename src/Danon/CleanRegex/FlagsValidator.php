<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Exception\FlagNotAllowedException;
use Danon\CleanRegex\Exception\InternalCleanRegexException;

class FlagsValidator
{
    private $flags = [
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

        if ($this->containWhitespace($flags)) {
            throw new FlagNotAllowedException("Flags cannot contain whitespace");
        }

        $this->validateFlags($flags);
    }

    private function containWhitespace(string $flags): bool
    {
        return preg_match('/\s/', $flags) === 1;
    }

    private function validateFlags(string $flags)
    {
        foreach (str_split($flags) as $flag) {
            if (!$this->isAllowed($flag)) {
                throw new FlagNotAllowedException("Regular expression flag '$flag' is not allowed");
            }
        }
    }

    private function isAllowed(string $character): bool
    {
        if (strlen($character) !== 1) {
            throw new InternalCleanRegexException('Flag must be one-character long');
        }
        return in_array($character, $this->flags);
    }
}
