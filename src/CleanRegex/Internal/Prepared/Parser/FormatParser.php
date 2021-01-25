<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Format\FormatTokenValue;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class FormatParser implements Parser
{
    /** @var string */
    private $format;
    /** @var array */
    private $tokens;

    public function __construct(string $format, array $tokens)
    {
        $this->format = $format;
        $this->tokens = $tokens;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable
    {
        return (new FormatTokenValue($this->format, $this->tokens))->formatAsQuotable();
    }

    public function getDelimiterable(): string
    {
        return \implode($this->tokens);
    }
}
