<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\CallbackStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreCallbackStrategy;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternInterface;

class PrepareFacade
{
    /** @var Parser */
    private $parser;
    /** @var bool */
    private $pcre;
    /** @var string */
    private $flags;

    public function __construct(Parser $parser, bool $pcre, string $flags)
    {
        $this->parser = $parser;
        $this->pcre = $pcre;
        $this->flags = $flags;
    }

    public function getPattern(): string
    {
        $delimiterer = new Delimiterer($this->strategy(function (string $delimiter) {
            return $this->parser->parse($delimiter, new AlterationFactory($this->flags))->quote($delimiter);
        }));
        return $delimiterer->delimiter($this->parser->getDelimiterable());
    }

    private function strategy(callable $patternProducer): DelimiterStrategy
    {
        if ($this->pcre) {
            return new PcreCallbackStrategy($patternProducer);
        }
        return new CallbackStrategy($patternProducer);
    }

    public static function build(Parser $parser, bool $pcre, string $flags): PatternInterface
    {
        try {
            return Pattern::pcre((new PrepareFacade($parser, $pcre, $flags))->getPattern() . $flags);
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }
}
