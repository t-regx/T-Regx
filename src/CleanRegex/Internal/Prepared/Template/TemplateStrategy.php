<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class TemplateStrategy implements TokenStrategy
{
    /** @var Token[] */
    private $tokens;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function nextAsQuotable(): Quotable
    {
        return $this->nextToken()->formatAsQuotable();
    }

    private function nextToken(): Token
    {
        $token = \current($this->tokens);
        \next($this->tokens);
        return $token;
    }
}
