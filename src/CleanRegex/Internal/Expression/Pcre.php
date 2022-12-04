<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Prepared\Expression\DelimiterExpression;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreSpelling;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\LiteralPlaceholders;

class Pcre implements Expression
{
    /** @var DelimiterExpression */
    private $expression;

    public function __construct(AutoCapture $autoCapture, string $pcre)
    {
        $this->expression = new DelimiterExpression($autoCapture, new PcreSpelling($pcre), new LiteralPlaceholders());
    }

    public function predefinition(): Predefinition
    {
        return $this->expression->predefinition();
    }
}
