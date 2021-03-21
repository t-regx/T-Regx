<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class RawToken implements Token
{
    /** @var string */
    private $literal;
    /** @var string */
    private $expectedDelimiter;

    public function __construct(string $literal, string $expectedDelimiter)
    {
        $this->literal = $literal;
        $this->expectedDelimiter = $expectedDelimiter;
    }

    public function formatAsQuotable(): Quotable
    {
        return new VerifyQuotable($this->literal, $this->expectedDelimiter);
    }
}
