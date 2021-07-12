<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;

class LimitlessReplacePattern extends ReplacePatternImpl
{
    public function __construct(SpecificReplacePattern $replacePattern, Definition $definition, string $subject)
    {
        parent::__construct($replacePattern, $definition, $subject, -1);
    }
}
