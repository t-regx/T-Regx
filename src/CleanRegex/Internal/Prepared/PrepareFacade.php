<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\CallbackStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreCallbackStrategy;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternInterface;

class PrepareFacade
{
    public static function build(Parser $parser, bool $pcre, string $flags): PatternInterface
    {
        try {
            $patternProducer = function (string $delimiter) use ($parser, $flags) {
                return $parser->parse($delimiter, new AlterationFactory($flags))->quote($delimiter);
            };
            $strategy = $pcre ? new PcreCallbackStrategy($patternProducer) : new CallbackStrategy($patternProducer);
            $delimiterer = new Delimiterer($strategy);
            $pattern = $delimiterer->delimiter($parser->getDelimiterable());
            return Pattern::pcre($pattern . $flags);
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }
}
