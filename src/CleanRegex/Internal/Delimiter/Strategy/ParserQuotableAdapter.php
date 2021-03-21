<?php
namespace TRegx\CleanRegex\Internal\Delimiter\Strategy;

use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class ParserQuotableAdapter implements Quotable
{
    /** @var Parser */
    private $parser;
    /** @var AlterationFactory */
    private $factory;

    public function __construct(Parser $parser, AlterationFactory $factory)
    {
        $this->parser = $parser;
        $this->factory = $factory;
    }

    public function quote(string $delimiter): string
    {
        return $this->parser->parse($delimiter, $this->factory)->quote($delimiter);
    }
}
