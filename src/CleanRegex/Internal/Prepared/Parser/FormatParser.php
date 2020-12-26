<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\UserInputQuoteable;

class FormatParser implements Parser
{
    /** @var string */
    private $pattern;
    /** @var array */
    private $tokens;

    public function __construct(string $pattern, array $tokens)
    {
        $this->pattern = $pattern;
        $this->tokens = $tokens;
    }

    public function getDelimiterable(): string
    {
        return '';
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quoteable
    {
        return new UserInputQuoteable($this->pattern);
    }
}
