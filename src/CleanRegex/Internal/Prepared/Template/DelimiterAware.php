<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Chars;

trait DelimiterAware
{
    abstract protected function delimiterAware(): string;

    public function suitable(string $candidate): bool
    {
        return !$this->textDelimiterAware()->contains($candidate);
    }

    private function textDelimiterAware(): Chars
    {
        return new Chars($this->delimiterAware());
    }
}
