<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

interface NonReplacedStrategy
{
    public function replacementResult(string $subject): ?string;
}
