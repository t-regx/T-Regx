<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\PatternAutoCapture;
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
    /** @var PatternAutoCapture */
    private $autoCapture;
    /** @var Spelling */
    private $spelling;

    public function __construct(AutoCapture $autoCapture, Spelling $spelling, Placeholders $placeholders)
    {
        $this->pattern = new PatternPhrase($autoCapture, $spelling, $placeholders);
        $this->autoCapture = $autoCapture;
        $this->spelling = $spelling;
    }

    public function predefinition(): Predefinition
    {
        try {
            return new DelimiterPredefinition(
                $this->autoCapture,
                $this->pattern->phrase(),
                $this->spelling->delimiter(),
                $this->spelling->flags());
        } catch (TrailingBackslashException $exception) {
            return new TrailingBackslashPredefinition(new PatternMalformedPatternException('Pattern may not end with a trailing backslash'));
        }
    }
}
