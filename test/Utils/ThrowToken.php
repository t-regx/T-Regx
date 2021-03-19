<?php
namespace Test\Utils;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class ThrowToken implements Token
{
    public function formatAsQuotable(): Quotable
    {
        throw new \AssertionError('Token wasn\'t supposed to be used');
    }
}
