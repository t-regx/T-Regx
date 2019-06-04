<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

interface ReplaceSubstitute
{
    public function substitute(string $subject): ?string;
}
