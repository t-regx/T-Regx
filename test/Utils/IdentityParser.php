<?php
namespace Test\Utils;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class IdentityParser implements Parser
{
    /** @var string */
    private $pattern;
    /** @var string */
    private $expectedDelimiter;

    public function __construct(string $pattern, string $expectedDelimiter)
    {
        $this->pattern = $pattern;
        $this->expectedDelimiter = $expectedDelimiter;
    }

    public function getDelimiterable(): string
    {
        return $this->pattern;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable
    {
        Assert::assertSame($this->expectedDelimiter, $delimiter, 'Failed to assert the expected delimiter');
        return new VerifyQuotable($this->pattern, $this->expectedDelimiter);
    }
}
