<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

trait DelimiterAware
{
    abstract protected function delimiterAware(): string;

    public function suitable(string $candidate): bool
    {
        return \strpos($this->delimiterAware(), $candidate) === false;
    }
}
