<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Expression\Predefinition\DelimiterPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\TrailingBackslashPredefinition;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\Placeholders;

class DelimiterExpression
{
    /** @var PatternPhrase */
    private $pattern;
    /** @var Spelling */
    private $spelling;

    public function __construct(Spelling $spelling, Placeholders $placeholders)
    {
        $this->pattern = new PatternPhrase($spelling, $placeholders);
        $this->spelling = $spelling;
    }

    public function predefinition(): Predefinition
    {
        try {
            return new DelimiterPredefinition(
                $this->pattern->phrase(),
                $this->spelling->delimiter(),
                $this->spelling->flags(),
                $this->spelling->undevelopedInput());
        } catch (TrailingBackslashException $exception) {
            return new TrailingBackslashPredefinition(new PatternMalformedPatternException('Pattern may not end with a trailing backslash'));
        }
    }
}
