<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Internal\TrailingBackslash;

class TemplateParser implements Parser
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

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable
    {
        TrailingBackslash::throwIfHas($this->pattern);
        $pattern = \preg_replace_callback('/&/', function () use ($delimiter): string {
            return $this->nextToken()->formatAsQuotable()->quote($delimiter);
        }, $this->pattern);
        return new RawQuotable($pattern);
    }

    private function nextToken(): Token
    {
        $token = \current($this->tokens);
        if ($token === false) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        \next($this->tokens);
        return $token;
    }

    public function getDelimiterable(): string
    {
        return $this->pattern;
    }
}
