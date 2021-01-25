<?php
namespace Test\Utils;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

class Identity implements QuotableFactory
{
    public function quotable($value): Quotable
    {
        return new RawQuotable($value);
    }
}
