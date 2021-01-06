<?php
namespace TRegx\CleanRegex\Internal\Format;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;

interface TokenValue
{
    public function formatAsQuotable(): Quoteable;
}
