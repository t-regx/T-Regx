<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
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

    public function formatAsQuotable(): Word
    {
        return new PatternWord($this->pattern);
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
