<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Type;
use TRegx\CleanRegex\Internal\ValueType;

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

    public function type(): Type
    {
        return new ValueType($this->values);
    }
}
