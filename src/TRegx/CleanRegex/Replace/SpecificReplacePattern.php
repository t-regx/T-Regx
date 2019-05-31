<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Replace\Map\ByReplacePattern;

interface SpecificReplacePattern
{
    public function with(string $replacement): string;

    public function withReferences(string $replacement): string;

    public function callback(callable $callback): string;

    public function by(): ByReplacePattern;
}
