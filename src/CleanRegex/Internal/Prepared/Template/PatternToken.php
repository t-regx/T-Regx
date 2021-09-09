<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\Type\PatternType;
use TRegx\CleanRegex\Internal\Type\Type;

class PatternToken implements Token
{
    use DelimiterAware;

    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function formatAsQuotable(): Quotable
    {
        return new RawQuotable($this->pattern);
    }

    public function type(): Type
    {
        return new PatternType($this->pattern);
    }

    protected function delimiterAware(): string
    {
        return $this->pattern;
    }
}
