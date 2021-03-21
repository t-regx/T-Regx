<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;

class PcreCallbackStrategy implements DelimiterStrategy
{
    /** @var callable */
    private $patternProducer;
    /** @var DelimiterParser */
    private $parser;

    public function __construct(callable $patternProducer)
    {
        $this->patternProducer = $patternProducer;
        $this->parser = new DelimiterParser();
    }

    public function buildPattern(string $delimiterable): string
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
        $delimiter = $this->parser->getDelimiter($delimiterable) ?? '/';

        return ($this->patternProducer)($delimiter);
    }
}
