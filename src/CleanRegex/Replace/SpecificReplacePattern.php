<?php
namespace TRegx\CleanRegex\Replace;

interface SpecificReplacePattern
{
    public function with(string $replacement): string;

    public function withReferences(string $replacement): string;

    public function callback(callable $callback): string;
}
