<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

interface DelimitablePhrase
{
    public function conjugated(string $delimiter): string;
}
