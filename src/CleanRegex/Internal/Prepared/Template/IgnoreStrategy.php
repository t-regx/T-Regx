<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

class IgnoreStrategy implements TokenStrategy
{
    public function nextAsQuotable(): Quotable
    {
        return new RawQuotable('&');
    }
}
