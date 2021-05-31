<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

trait QuotesRaw
{
    public abstract function raw();

    public function quotable(): Quotable
    {
        return new RawQuotable($this->raw());
    }
}
