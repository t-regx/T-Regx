<?php
namespace TRegx\CleanRegex\Internal\Expression;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Expression\Predefinition\DelimiterPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

trait StrictInterpretation
{
    abstract protected function phrase(): Phrase;

    abstract protected function delimiter(): Delimiter;

    abstract protected function flags(): Flags;

    abstract protected function undevelopedInput(): string;

    public function predefinition(): Predefinition
    {
        $phrase = $this->phrase();
        return new DelimiterPredefinition($this->delimiter(), $this->flags(), $phrase, $this->undevelopedInput());
    }
}
