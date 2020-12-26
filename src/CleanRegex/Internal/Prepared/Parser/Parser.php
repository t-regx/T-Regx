<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;

interface Parser
{
    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quoteable;

    public function getDelimiterable(): string;
}
