<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface TokenStrategy
{
    public function nextAsQuotable(): Quotable;
}
