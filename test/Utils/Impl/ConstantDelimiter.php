<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class ConstantDelimiter implements DelimiterStrategy
{
    /** @var AlterationFactory */
    private $alterationFactory;

    public function __construct(AlterationFactory $alterationFactory)
    {
        $this->alterationFactory = $alterationFactory;
    }

    public function buildPattern(string $delimiterable, Quotable $pattern): string
    {
        return $pattern->quote("\1");
    }

    public function getAlternationFactory(): AlterationFactory
    {
        return $this->alterationFactory;
    }
}
