<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Expression\Predefinition\DelimiterPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\TrailingBackslashPredefinition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

trait PredefinedExpression
{
    abstract protected function phrase(): Phrase;

    abstract protected function delimiter(): Delimiter;

    abstract protected function flags(): Flags;

    abstract protected function undevelopedInput(): string;

    public function predefinition(): Predefinition
    {
        try {
            return new DelimiterPredefinition($this->delimiter(),
                $this->flags(),
                $this->phrase(),
                $this->undevelopedInput());
        } catch (TrailingBackslashException $exception) {
            return new TrailingBackslashPredefinition(new PatternMalformedPatternException('Pattern may not end with a trailing backslash'));
        }
    }
}
