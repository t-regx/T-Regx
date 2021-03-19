<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class TemplateStrategy implements TokenStrategy
{
    /** @var Token[] */
    private $placeholders;

    public function __construct(array $placeholders)
    {
        $this->placeholders = $placeholders;
    }

    public function nextAsQuotable(): Quotable
    {
        return $this->nextPlaceholder()->formatAsQuotable();
    }

    private function nextPlaceholder(): Token
    {
        $placeholder = \current($this->placeholders);
        \next($this->placeholders);
        return $placeholder;
    }
}
