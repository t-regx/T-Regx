<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\SingleFigure;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\AlterationToken;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\Internal\Prepared\Template\PatternToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Pattern;

class PatternTemplate
{
    /** @var Orthography */
    private $orthography;

    public function __construct(Orthography $orthography)
    {
        $this->orthography = $orthography;
    }

    public function mask(string $mask, array $keywords): Pattern
    {
        return $this->template(new MaskToken($mask, $keywords));
    }

    public function literal(string $text): Pattern
    {
        return $this->template(new LiteralToken($text));
    }

    public function alteration(array $figures): Pattern
    {
        return $this->template(new AlterationToken($figures));
    }

    public function pattern(string $pattern): Pattern
    {
        return $this->template(new PatternToken($pattern));
    }

    private function template(Token $token): Pattern
    {
        return new Pattern(new Template($this->orthography->spelling($token), new SingleFigure($token)));
    }
}
