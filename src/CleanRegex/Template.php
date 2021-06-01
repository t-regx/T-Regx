<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;

class Template
{
    /** @var string */
    private $pattern;
    /** @var DelimiterStrategy */
    private $strategy;

    public function __construct(string $pattern, DelimiterStrategy $strategy)
    {
        $this->pattern = $pattern;
        $this->strategy = $strategy;
    }

    public function inject(array $values): Pattern
    {
        return $this->builder()->inject($values);
    }

    public function mask(string $mask, array $keywords): Pattern
    {
        return $this->builder()->mask($mask, $keywords)->build();
    }

    public function literal(string $text): Pattern
    {
        return $this->builder()->literal($text)->build();
    }

    public function builder(): TemplateBuilder
    {
        return new TemplateBuilder($this->pattern, $this->strategy, []);
    }
}
