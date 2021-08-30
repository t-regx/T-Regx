<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Type;
use TRegx\CleanRegex\Internal\ValueType;

class AlternationToken implements Token
{
    /** @var array */
    private $figures;

    public function __construct(array $figures)
    {
        $this->figures = $figures;
    }

    public function formatAsQuotable(): Quotable
    {
        return new AlternationQuotable($this->figures);
    }

    public function type(): Type
    {
        return new ValueType($this->figures);
    }
}
