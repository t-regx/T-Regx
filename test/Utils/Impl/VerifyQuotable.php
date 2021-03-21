<?php
namespace Test\Utils\Impl;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class VerifyQuotable implements Quotable
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

    public function quote(string $delimiter): string
    {
        Assert::assertSame($this->expectedDelimiter, $delimiter, 'Failed to assert the expected delimiter');
        return $this->literal;
    }
}
