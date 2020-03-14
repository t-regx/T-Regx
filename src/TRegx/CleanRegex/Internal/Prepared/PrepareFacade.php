<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\CallbackStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreCallbackStrategy;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;

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
            return $this->parser->parse($delimiter, new QuotableFactory($this->flags))->quote($delimiter);
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
}
