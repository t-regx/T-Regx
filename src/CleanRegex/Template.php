<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;

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
        return (new TemplateBuilder($this->pattern, $this->flags, $this->pcre, []))->inject($values);
    }

    public function bind(array $values): PatternInterface
    {
        return (new TemplateBuilder($this->pattern, $this->flags, $this->pcre, []))->bind($values);
    }

    public function mask(string $string, array $tokens): PatternInterface
    {
        return $this->putMask($string, $tokens)->build();
    }

    public function literal(string $text): PatternInterface
    {
        return $this->putLiteral($text)->build();
    }

    public function putMask(string $mask, array $keywords): TemplateBuilder
    {
        return new TemplateBuilder($this->pattern, $this->flags, $this->pcre, [new MaskToken($mask, $keywords)]);
    }

    public function putLiteral(string $text): TemplateBuilder
    {
        return new TemplateBuilder($this->pattern, $this->flags, $this->pcre, [new LiteralToken($text)]);
    }
}
