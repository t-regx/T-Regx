<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Prepared\Format\TokenValue;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\QuotableFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\TrailingBackslash;

class TemplateParser implements Parser
{
    /** @var string */
    private $pattern;
    /** @var array */
    private $placeholders;

    public function __construct(string $pattern, array $placeholders)
    {
        $this->pattern = $pattern;
        $this->placeholders = $placeholders;
    }

    public function parse(string $delimiter, QuotableFactory $quotableFactory): Quotable
    {
        TrailingBackslash::throwIfHas($this->pattern);
        $pattern = \preg_replace_callback('/&/', function (): string {
            return $this->nextPlaceholder()->formatAsQuotable()->quote('/');
        }, $this->pattern);
        return new RawQuotable($pattern);
    }

    private function nextPlaceholder(): TokenValue
    {
        $placeholder = \current($this->placeholders);
        if ($placeholder === false) {
            // @codeCoverageIgnoreStart
            throw new InternalCleanRegexException();
            // @codeCoverageIgnoreEnd
        }
        \next($this->placeholders);
        return $placeholder;
    }

    public function getDelimiterable(): string
    {
        return $this->pattern;
    }
}
