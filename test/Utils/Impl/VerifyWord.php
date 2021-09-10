<?php
namespace Test\Utils\Impl;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class VerifyWord implements Word
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

    public function quoted(string $delimiter): string
    {
        Assert::assertSame($this->expectedDelimiter, $delimiter, 'Failed to assert the expected delimiter');
        return $this->literal;
    }
}
