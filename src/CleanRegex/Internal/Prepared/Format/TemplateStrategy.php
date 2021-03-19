<?php
namespace TRegx\CleanRegex\Internal\Prepared\Format;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class TemplateStrategy implements TokenStrategy
{
    /** @var TokenValue[] */
    private $placeholders;

    public function __construct(array $placeholders)
    {
        $this->placeholders = $placeholders;
    }

    public function nextAsQuotable(): Quotable
    {
        return $this->nextPlaceholder()->formatAsQuotable();
    }

    private function nextPlaceholder(): TokenValue
    {
        $placeholder = \current($this->placeholders);
        \next($this->placeholders);
        return $placeholder;
    }
}
