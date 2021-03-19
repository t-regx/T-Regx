<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;

class MaskParser implements Parser
{
    /** @var string */
    private $mask;
    /** @var array */
    private $keywords;

    public function __construct(string $mask, array $keywords)
    {
        $this->mask = $mask;
        $this->keywords = $keywords;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable
    {
        return (new MaskToken($this->mask, $this->keywords))->formatAsQuotable();
    }

    public function getDelimiterable(): string
    {
        return \implode($this->keywords);
    }
}
