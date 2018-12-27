<?php
namespace TRegx\CleanRegex\Replace;

interface ReplacePattern
{
    public function with(string $replacement): string;

    public function withReferences(string $replacement): string;

    public function callback(callable $callback): string;

    public function by(): MapReplacePattern;
}
