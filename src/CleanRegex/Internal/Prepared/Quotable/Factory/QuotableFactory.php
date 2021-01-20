<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable\Factory;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface QuotableFactory
{
    public function quotable($value): Quotable;
}
