<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreCallbackStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\CallbackStrategy;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;

class PrepareFacade
{
    /** @var Parser */
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function getPattern(): string
    {
        $delimiter = new Delimiterer(new CrossDelimiterStrategy(function (string $delimiter) {
            return $this->parser->parse($delimiter)->quote($delimiter);
        }));
        return $delimiter->delimiter($this->parser->getDelimiterable());
    }

    private function strategy(callable $patternProducer): DelimiterStrategy
    {
        if ($this->pcre) {
            return new PcreCallbackStrategy($patternProducer);
        }
        return new CallbackStrategy($patternProducer);
    }
}
