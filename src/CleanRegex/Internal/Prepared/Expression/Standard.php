<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
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

    public function __construct(AutoCapture $autoCapture, Spelling $spelling)
    {
        $this->expression = new DelimiterExpression($autoCapture, $spelling, new LiteralPlaceholders());
        $this->spelling = $spelling;
    }

    public function predefinition(): Predefinition
    {
        try {
            return $this->expression->predefinition();
        } catch (UndelimitablePatternException $exception) {
            throw new ExplicitDelimiterRequiredException("pattern: {$this->spelling->pattern()}");
        }
    }
}
