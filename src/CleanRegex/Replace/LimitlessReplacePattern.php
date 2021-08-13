<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subjectable;

class LimitlessReplacePattern extends ReplacePatternImpl
{
    public function __construct(SpecificReplacePattern $replacePattern, Definition $definition, Subjectable $subject)
    {
        parent::__construct($replacePattern, $definition, $subject, -1);
    }
}
