<?php
namespace TRegx\CleanRegex\Internal\Prepared\Format;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

class LiteralTokenValue implements TokenValue
{
    public function formatAsQuotable(): Quotable
    {
        return new RawQuotable('&');
    }
}
