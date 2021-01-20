<?php
namespace TRegx\CleanRegex\Internal\Format;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

class LiteralValue implements TokenValue
{
    public function formatAsQuotable(): Quotable
    {
        return new RawQuotable('&');
    }
}
