<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

class LimitlessReplacePattern extends ReplacePatternImpl
{
    public function __construct(SpecificReplacePattern $replacePattern, Definition $definition, Subject $subject)
    {
        parent::__construct($replacePattern, $definition, $subject, -1);
    }
}
