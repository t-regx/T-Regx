<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\Format\TokenValue;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;

class TemplateStrategy implements TokenStrategy
{
    /** @var TokenValue[] */
    private $placeholders;

    public function __construct(array $placeholders)
    {
        $this->placeholders = $placeholders;
    }

    public function nextAsQuotable(): Quoteable
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
