<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Type;

class AlternationToken implements Token
{
    /** @var array */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function formatAsQuotable(): Quotable
    {
        return new AlternationQuotable($this->values);
    }

    public function type(): string
    {
        return Type::asString($this->values);
    }
}
