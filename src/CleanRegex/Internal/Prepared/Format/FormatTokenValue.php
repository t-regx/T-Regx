<?php
namespace TRegx\CleanRegex\Internal\Prepared\Format;

use TRegx\CleanRegex\Exception\FormatMalformedPatternException;
use TRegx\CleanRegex\Internal\Format\TokenValue;
use TRegx\CleanRegex\Internal\MultiSplitter;
use TRegx\CleanRegex\Internal\Prepared\Quotable\CompositeQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\TrailingBackslash;
use TRegx\CleanRegex\Internal\ValidPattern;

class FormatTokenValue implements TokenValue
{
    /** @var string */
    private $format;
    /** @var array */
    private $tokens;

    public function __construct(string $format, array $tokens)
    {
        $this->format = $format;
        $this->tokens = $tokens;
    }

    public function formatAsQuotable(): Quotable
    {
        foreach ($this->tokens as $placeholder => $pattern) {
            $this->validatePair($pattern, $placeholder);
        }
        foreach ($this->tokens as $placeholder => $pattern) {
            $this->validateEmpty($placeholder);
        }
        return new CompositeQuotable($this->quotableTokens((new MultiSplitter($this->format, \array_keys($this->tokens)))->split()));
    }

    private function quotableTokens(array $elements): array
    {
        $quotes = [];
        foreach ($elements as $rawOrToken) {
            $quotes[] = \array_key_exists($rawOrToken, $this->tokens)
                ? new RawQuotable($this->tokens[$rawOrToken])
                : new UserInputQuotable($rawOrToken);
        }
        return $quotes;
    }

    private function validatePair(string $pattern, string $placeholder): void
    {
        if (TrailingBackslash::hasTrailingSlash($pattern) || !ValidPattern::isValidStandard($pattern)) {
            throw new FormatMalformedPatternException("Malformed pattern '$pattern' assigned to placeholder '$placeholder'");
        }
    }

    public function validateEmpty(string $placeholder): void
    {
        if ($placeholder === '') {
            throw new \InvalidArgumentException("Placeholder cannot be empty, must consist of at least one character");
        }
    }
}
