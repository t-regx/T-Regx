<?php
namespace TRegx\CleanRegex\Internal\Replace;

class ReplaceReferences
{
    public static function escaped(string $replacement): string
    {
        return \str_replace(['\\', '$'], ['\\\\', '\\$'], $replacement);
    }
}
