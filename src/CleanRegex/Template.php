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

    public function putMask(string $mask, array $keywords): TemplateBuilder
    {
        return new TemplateBuilder($this->pattern, $this->flags, $this->pcre, [new MaskToken($mask, $keywords)]);
    }

    public function putLiteral(): TemplateBuilder
    {
        return new TemplateBuilder($this->pattern, $this->flags, $this->pcre, [new LiteralToken()]);
    }

    public function mask(string $string, array $tokens): PatternInterface
    {
        $template = new TemplateBuilder($this->pattern, $this->flags, $this->pcre, [new MaskToken($string, $tokens)]);
        return $template->build();
    }

    public function inject(array $values): PatternInterface
    {
        $template = new TemplateBuilder($this->pattern, $this->flags, $this->pcre, []);
        return $template->inject($values);
    }

    public function bind(array $values): PatternInterface
    {
        $template = new TemplateBuilder($this->pattern, $this->flags, $this->pcre, []);
        return $template->bind($values);
    }
}
