<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

trait DelimiterAgnostic
{
    public function suitable(string $candidate): bool
    {
        return true;
    }
}
