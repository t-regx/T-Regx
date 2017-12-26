<?php
namespace CleanRegex;

use CleanRegex\Exception\CleanRegex\FlagNotAllowedException;
use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\preg;

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
        try {
            return preg::match('/\s/', $flags) === 1;
        } catch (SafeRegexException $exception) {
            throw new InternalCleanRegexException();
        }
    }

    private function validateFlags(string $flags): void
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
