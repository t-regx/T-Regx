<?php
namespace TRegx\CleanRegex\Replace;

interface CompositeReplacePattern extends SpecificReplacePattern
{
    public function focus($nameOrIndex): FocusReplacePattern;
}
