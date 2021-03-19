<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Exception\TemplateFormatException;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\TemplateParser;
use TRegx\CleanRegex\Internal\Prepared\Prepare;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\Internal\Prepared\Template\TemplateStrategy;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;

class TemplateBuilder
{
    /** @var string */
    private $pattern;
    /** @var bool */
    private $pcre;
    /** @var string */
    private $flags;
    /** @var Token[] */
    private $placeholders;

    public function __construct(string $pattern, string $flags, bool $pcre, array $placeholders)
    {
        $this->pattern = $pattern;
        $this->pcre = $pcre;
        $this->flags = $flags;
        $this->placeholders = $placeholders;
    }

    public function putMask(string $mask, array $keywords): TemplateBuilder
    {
        return $this->next(new MaskToken($mask, $keywords));
    }

    public function putLiteral(): TemplateBuilder
    {
        return $this->next(new LiteralToken());
    }

    private function next(Token $placeholder): TemplateBuilder
    {
        return new TemplateBuilder($this->pattern, $this->flags, $this->pcre, \array_merge($this->placeholders, [$placeholder]));
    }

    public function build(): PatternInterface
    {
        $this->validateTokensAndMethods();
        return Prepare::build(new TemplateParser($this->pattern, $this->placeholders), $this->pcre, $this->flags);
    }

    public function inject(array $values): PatternInterface
    {
        $this->validateTokensAndMethods();
        return Prepare::build(new InjectParser($this->pattern, $values, new TemplateStrategy($this->placeholders)), $this->pcre, $this->flags);
    }

    public function bind(array $values): PatternInterface
    {
        $this->validateTokensAndMethods();
        return Prepare::build(new BindingParser($this->pattern, $values, new TemplateStrategy($this->placeholders)), $this->pcre, $this->flags);
    }

    private function validateTokensAndMethods(): void
    {
        $tokens = \preg_match_all('/&/', $this->pattern);
        $count = \count($this->placeholders);
        if ($tokens < $count) {
            throw TemplateFormatException::insufficient($tokens, $count);
        }
        if ($tokens > $count) {
            throw TemplateFormatException::superfluous($tokens, $count);
        }
    }
}
