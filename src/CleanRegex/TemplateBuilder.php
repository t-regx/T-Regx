<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Exception\TemplateFormatException;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\TemplateParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\Internal\Prepared\Template\TemplateStrategy;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class TemplateBuilder
{
    /** @var string */
    private $pattern;
    /** @var DelimiterStrategy */
    private $strategy;
    /** @var Token[] */
    private $tokens;

    public function __construct(string $pattern, DelimiterStrategy $strategy, array $tokens)
    {
        $this->pattern = $pattern;
        $this->strategy = $strategy;
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
        return new TemplateBuilder($this->pattern, $this->strategy, \array_merge($this->tokens, [$token]));
    }

    public function build(): Pattern
    {
        $this->validateTokensAndMethods();
        return PrepareFacade::build(new TemplateParser($this->pattern, $this->tokens), $this->strategy);
    }

    public function inject(array $values): Pattern
    {
        $this->validateTokensAndMethods();
        return PrepareFacade::build(new InjectParser($this->pattern, $values, new TemplateStrategy($this->tokens)), $this->strategy);
    }

    public function bind(array $values): Pattern
    {
        $this->validateTokensAndMethods();
        return PrepareFacade::build(new BindingParser($this->pattern, $values, new TemplateStrategy($this->tokens)), $this->strategy);
    }

    private function validateTokensAndMethods(): void
    {
        $placeholders = \preg_match_all('/&/', $this->pattern);
        $tokens = \count($this->tokens);
        if ($placeholders < $tokens) {
            throw TemplateFormatException::insufficient($placeholders, $tokens);
        }
        if ($placeholders > $tokens) {
            throw TemplateFormatException::superfluous($placeholders, $tokens);
        }
    }
}
