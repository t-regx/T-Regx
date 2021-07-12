<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\TokenFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Pattern;

class TemplateBuilder
{
    /** @var Orthography */
    private $orthography;
    /** @var Token[] */
    private $tokens;

    public function __construct(Orthography $orthography, array $tokens)
    {
        $this->orthography = $orthography;
        $this->tokens = $tokens;
    }

    public function mask(string $mask, array $keywords): TemplateBuilder
    {
        return $this->next(new MaskToken($mask, $keywords));
    }

    public function literal(string $text): TemplateBuilder
    {
        return $this->next(new LiteralToken($text));
    }

    private function next(Token $token): TemplateBuilder
    {
        return new TemplateBuilder($this->orthography, \array_merge($this->tokens, [$token]));
    }

    public function build(): Pattern
    {
        $build = new Template($this->orthography, new TokenFigures($this->tokens));
        return new Pattern($build->definition());
    }
}
