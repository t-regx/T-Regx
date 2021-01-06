<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;

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

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quoteable
    {
        return (new FormatTokenValue($this->pattern, $this->tokens))->formatAsQuotable();
    }

    public function getDelimiterable(): string
    {
        return \implode($this->tokens);
    }
}
