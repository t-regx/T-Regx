<?php
namespace TRegx\CleanRegex\Replace;

interface ReplacePattern extends SpecificReplacePattern
{
    public function counting(callable $countReceiver): SpecificReplacePattern;
}
