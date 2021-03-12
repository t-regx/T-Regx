<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

class PcreCallbackStrategy implements DelimiterStrategy
{
    /** @var callable */
    private $patternProducer;

    public function __construct(callable $patternProducer)
    {
        $this->patternProducer = $patternProducer;
    }

    public function buildPattern(string $pattern, ?string $delimiter): string
    {
        /**
         * It may be possible that prepared patterns, are used in PCRE mode,
         * and are fed a pattern that's not properly delimited. In that case,
         * DelimiterParser won't be able to extract the delimiter, and
         * this DelimiterStrategy will be fed {@see null} in place of $delimiter.
         * I could throw an exception here, but I decided that it's better
         * to ignore it here, let invalid that pattern be further passed to
         * PCRE and let the preg_*() method throw the proper exception and
         * message to the user. That way, it will be obvious that it's a
         * problem with the pattern, and not the library.
         */
        $delimiter = $delimiter ?? '/';

        return ($this->patternProducer)($delimiter);
    }

    public function shouldGuessDelimiter(): bool
    {
        return false;
    }
}
