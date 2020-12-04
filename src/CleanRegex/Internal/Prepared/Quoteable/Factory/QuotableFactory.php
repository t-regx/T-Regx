<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;

interface QuotableFactory
{
    public function quotable($value): Quoteable;
}
