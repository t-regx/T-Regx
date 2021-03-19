<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

class LiteralToken implements Token
{
    public function formatAsQuotable(): Quotable
    {
        return new RawQuotable('&');
    }
}
