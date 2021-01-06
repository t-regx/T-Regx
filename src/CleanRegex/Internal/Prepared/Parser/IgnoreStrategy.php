<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;

class IgnoreStrategy implements TokenStrategy
{
    public function nextAsQuotable(): Quoteable
    {
        return new RawQuoteable('&');
    }
}
