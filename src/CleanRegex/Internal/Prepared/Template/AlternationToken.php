<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Type\Type;
use TRegx\CleanRegex\Internal\Type\ValueType;

class AlternationToken implements Token
{
    use DelimiterAgnostic;

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
