<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternInterface;

class Prepare
{
    public static function build(Parser $parser, bool $pcre, string $flags): PatternInterface
    {
        try {
            return Pattern::pcre((new PrepareFacade($parser, $pcre, $flags))->getPattern() . $flags);
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }
}
