<?php
namespace TRegx\CleanRegex\Internal\Format;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\RawQuoteable;

class LiteralValue implements TokenValue
{
    public function formatAsQuotable(): Quoteable
    {
        return new RawQuoteable('&');
    }
}
