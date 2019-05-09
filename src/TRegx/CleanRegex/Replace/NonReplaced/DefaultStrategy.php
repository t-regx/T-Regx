<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

class DefaultStrategy implements NonReplacedStrategy
{
    public function replacementResult(string $subject): ?string
    {
        return null;
    }
}
