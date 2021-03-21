<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\ParserQuotableAdapter;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\StandardStrategy;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternInterface;

class PrepareFacade
{
    public static function build(Parser $parser, bool $pcre, string $flags): PatternInterface
    {
        $strategy = $pcre ? new PcreStrategy() : new StandardStrategy();
        $pattern = new ParserQuotableAdapter($parser, new AlterationFactory($flags));

        try {
            return Pattern::pcre($strategy->buildPattern($parser->getDelimiterable(), $pattern) . $flags);
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }
}
