<?php
namespace TRegx\CleanRegex\Replace;

interface CompositeReplacePattern extends SpecificReplacePattern
{
    /**
     * @deprecated
     */
    public function focus($nameOrIndex): FocusReplacePattern;
}
