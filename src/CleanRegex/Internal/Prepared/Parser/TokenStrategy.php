<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;

interface TokenStrategy
{
    public function nextAsQuotable(): Quoteable;
}
