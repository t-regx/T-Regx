<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\LiteralPlaceholders;

class Standard implements Expression
{
    /** @var DelimiterExpression */
    private $expression;
    /** @var Spelling */
    private $spelling;

    public function __construct(Spelling $spelling)
    {
        $this->expression = new DelimiterExpression($spelling, new LiteralPlaceholders());
        $this->spelling = $spelling;
    }

    public function predefinition(): Predefinition
    {
        try {
            return $this->expression->predefinition();
        } catch (UndelimitablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forStandard($this->spelling->pattern());
        }
    }
}
