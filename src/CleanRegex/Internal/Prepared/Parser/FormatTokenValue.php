<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Format\TokenValue;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\MultiSplitter;
use TRegx\CleanRegex\Internal\Prepared\Quotable\CompositeQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\ValidPattern;

class FormatTokenValue implements TokenValue
{
    /** @var string */
    private $pattern;
    /** @var array */
    private $tokens;

    public function __construct(string $pattern, array $tokens)
    {
        $this->pattern = $pattern;
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
        return new CompositeQuotable($this->quotableTokens((new MultiSplitter($this->pattern, \array_keys($this->tokens)))->split()));
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
        if (!ValidPattern::isValid(InternalPattern::standard($pattern)->pattern)) {
            throw new \InvalidArgumentException("Malformed pattern '$pattern' assigned to placeholder '$placeholder'");
        }
    }

    public function validateEmpty(string $placeholder): void
    {
        if ($placeholder === '') {
            throw new \InvalidArgumentException("Placeholder cannot be empty, must consist of at least one character");
        }
    }
}
