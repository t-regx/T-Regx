<?php
namespace TRegx\CleanRegex\Internal\Prepared\Format;

use TRegx\CleanRegex\Exception\FormatMalformedPatternException;
use TRegx\CleanRegex\Internal\MultiSplitter;
use TRegx\CleanRegex\Internal\Prepared\Quotable\CompositeQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\TrailingBackslash;
use TRegx\CleanRegex\Internal\ValidPattern;

class MaskTokenValue implements TokenValue
{
    /** @var string */
    private $mask;
    /** @var array */
    private $keywords;

    public function __construct(string $mask, array $keywords)
    {
        $this->mask = $mask;
        $this->keywords = $keywords;
    }

    public function formatAsQuotable(): Quotable
    {
        foreach ($this->keywords as $keyword => $pattern) {
            $this->validatePair($pattern, $keyword);
        }
        foreach ($this->keywords as $keyword => $pattern) {
            $this->validateEmpty($keyword);
        }
        return new CompositeQuotable($this->quotableTokens((new MultiSplitter($this->mask, \array_keys($this->keywords)))->split()));
    }

    private function quotableTokens(array $elements): array
    {
        $quotes = [];
        foreach ($elements as $rawOrToken) {
            $quotes[] = \array_key_exists($rawOrToken, $this->keywords)
                ? new RawQuotable($this->keywords[$rawOrToken])
                : new UserInputQuotable($rawOrToken);
        }
        return $quotes;
    }

    private function validatePair(string $pattern, string $keyword): void
    {
        if (TrailingBackslash::hasTrailingSlash($pattern) || !ValidPattern::isValidStandard($pattern)) {
            throw new FormatMalformedPatternException("Malformed pattern '$pattern' assigned to keyword '$keyword'");
        }
    }

    public function validateEmpty(string $keyword): void
    {
        if ($keyword === '') {
            throw new \InvalidArgumentException("Keyword cannot be empty, must consist of at least one character");
        }
    }
}
