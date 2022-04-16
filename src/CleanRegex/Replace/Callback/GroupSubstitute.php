<?php
namespace TRegx\CleanRegex\Replace\Callback;

interface GroupSubstitute
{
    public function substitute(string $fallback): string;
}
