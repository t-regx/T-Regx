<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\SafeRegex\preg;

class Filter
{
    /** @var Predefinition */
    private $predefinition;

    public function __construct(Predefinition $predefinition)
    {
        $this->predefinition = $predefinition;
    }

    public function filtered(SubjectList $subjects): array
    {
        return \array_values(preg::grep($this->predefinition->definition()->pattern, $subjects->subjects));
    }

    public function rejected(SubjectList $subjects): array
    {
        return \array_values(preg::grep($this->predefinition->definition()->pattern, $subjects->subjects, \PREG_GREP_INVERT));
    }
}
