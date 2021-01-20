<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface Parser
{
    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable;

    public function getDelimiterable(): string;
}
