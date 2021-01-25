<?php
namespace TRegx\CleanRegex\Internal\Prepared\Format;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface TokenStrategy
{
    public function nextAsQuotable(): Quotable;
}
