<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

class DefaultStrategy implements ReplaceSubstitute
{
    public function substitute(string $subject): ?string
    {
        return null;
    }
}
