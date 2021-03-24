<?php
namespace TRegx\CleanRegex;

class Template
{
    /** @var string */
    private $pattern;
    /** @var bool */
    private $pcre;
    /** @var string */
    private $flags;

    public function __construct(string $pattern, string $flags, bool $pcre)
    {
        $this->pattern = $pattern;
        $this->pcre = $pcre;
        $this->flags = $flags;
    }

    public function inject(array $values): PatternInterface
    {
        return $this->builder()->inject($values);
    }

    public function bind(array $values): PatternInterface
    {
        return $this->builder()->bind($values);
    }

    public function mask(string $mask, array $keywords): PatternInterface
    {
        return $this->builder()->mask($mask, $keywords)->build();
    }

    public function literal(string $text): PatternInterface
    {
        return $this->builder()->literal($text)->build();
    }

    public function builder(): TemplateBuilder
    {
        return new TemplateBuilder($this->pattern, $this->flags, $this->pcre, []);
    }
}
