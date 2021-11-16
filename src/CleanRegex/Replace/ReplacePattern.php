<?php
namespace TRegx\CleanRegex\Replace;

interface ReplacePattern extends CompositeReplacePattern
{
    public function counting(callable $countReceiver): CompositeReplacePattern;
}
