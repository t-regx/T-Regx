<?php
namespace TRegx\CleanRegex\Internal\Format;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface TokenValue
{
    public function formatAsQuotable(): Quotable;
}
