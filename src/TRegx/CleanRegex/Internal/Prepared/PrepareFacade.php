<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\CrossDelimiterStrategy;
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
}
