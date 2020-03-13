<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;

interface Parser
{
    public function getDelimiterable(): string;

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quoteable;
}
