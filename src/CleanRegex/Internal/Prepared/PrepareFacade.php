<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\ParserQuotableAdapter;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Pattern;

class PrepareFacade
{
    public static function build(Parser $parser, DelimiterStrategy $strategy): Pattern
    {
        $pattern = new ParserQuotableAdapter($parser, $strategy->getAlternationFactory());

        try {
            return Pattern::pcre($strategy->buildPattern($parser->getDelimiterable(), $pattern));
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }
}
