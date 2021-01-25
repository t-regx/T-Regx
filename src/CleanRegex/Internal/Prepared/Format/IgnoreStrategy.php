<?php
namespace TRegx\CleanRegex\Internal\Prepared\Format;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

class IgnoreStrategy implements TokenStrategy
{
    public function nextAsQuotable(): Quotable
    {
        return new RawQuotable('&');
    }
}
